<?php

namespace App\Http\Controllers;

use App\Models\Inventaire;
use App\Models\InventaireDetail;
use App\Models\Immobilisation;
use App\Models\Site;
use App\Models\Service;
use App\Models\Dossier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class InventaireController extends Controller
{
    /**
     * Mapping des valeurs de statut entre l'interface et la base de données
     */
    private $statutMapping = [
        'Trouvé' => 'trouve',
        'Non trouvé en base' => 'non_trouve_base',
        'Non trouvé physiquement' => 'non_trouve_physique',
        'Écart de localisation' => 'ecart_localisation',
        'Écart d\'état' => 'ecart_etat',
        'Endommagé' => 'endommage'
    ];

    /**
     * Mapping inverse des valeurs de statut pour l'affichage
     */
    private $statutMappingReverse = [
        'trouve' => 'Trouvé',
        'non_trouve_base' => 'Non trouvé en base',
        'non_trouve_physique' => 'Non trouvé physiquement',
        'ecart_localisation' => 'Écart de localisation',
        'ecart_etat' => 'Écart d\'état',
        'endommage' => 'Endommagé'
    ];

    /**
     * Récupère l'ID du dossier courant depuis la session
     */
    private function getCurrentDossierId()
    {
        return session("current_dossier_id") ?? session("dossier_id");
    }

    /**
     * Display a listing of the inventory sessions.
     */
    public function index(): View
    {
        $dossierId = $this->getCurrentDossierId();
        $inventaires = Inventaire::where("dossier_id", $dossierId)
                                 ->with("responsable") // Eager load responsable
                                 ->orderBy("date_debut", "desc")
                                 ->paginate(15); // Add pagination

        // Convertir les statuts pour l'affichage
        foreach ($inventaires as $inventaire) {
            $inventaire->statut_display = $this->statutMappingReverse[$inventaire->statut] ?? 'Planifié';
        }

        return view("inventaires.index", compact("inventaires"));
    }

    /**
     * Show the form for creating a new inventory session.
     */
    public function create(): View
    {
        // Passer les options de statut au formulaire
        $statutOptions = [
            'Planifié' => 'Planifié',
            'En cours' => 'En cours',
            'Terminé' => 'Terminé',
            'Annulé' => 'Annulé'
        ];
        
        return view("inventaires.create", compact('statutOptions'));
    }

    /**
     * Store a newly created inventory session in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $dossierId = $this->getCurrentDossierId();
        $validatedData = $request->validate([
            "reference" => ["required", "string", "max:255", "unique:inventaires,reference,NULL,id,dossier_id,".$dossierId],
            "date_debut" => ["required", "date"],
            "description" => ["nullable", "string"],
            "statut" => ["required", "in:Planifié,En cours,Terminé,Annulé"],
            "site_id" => ["nullable", "exists:sites,id"],
            "service_id" => ["nullable", "exists:services,id"],
        ]);

        // Convertir le statut du formulaire vers la valeur de la base de données
        $dbStatut = 'planifie';
        if ($validatedData["statut"] === 'En cours') {
            $dbStatut = 'en_cours';
        } elseif ($validatedData["statut"] === 'Terminé') {
            $dbStatut = 'termine';
        } elseif ($validatedData["statut"] === 'Annulé') {
            $dbStatut = 'annule';
        }

        // Si le statut est "Terminé", définir la date de fin si elle n'est pas déjà définie
        $dateFin = null;
        if ($dbStatut == "termine" && empty($validatedData["date_fin"])) {
            $dateFin = Carbon::now();
        }

        Inventaire::create([
            "dossier_id" => $dossierId,
            "responsable_id" => Auth::id(),
            "reference" => $validatedData["reference"],
            "date_debut" => $validatedData["date_debut"],
            "date_fin" => $dateFin,
            "description" => $validatedData["description"],
            "statut" => $dbStatut,
            "site_id" => $validatedData["site_id"] ?? null,
            "service_id" => $validatedData["service_id"] ?? null,
        ]);

        return redirect()->route("inventaires.index")
                         ->with("success", "Session d'inventaire créée avec succès.");
    }

    /**
     * Display the specified inventory session and its counts.
     */
    public function show(Inventaire $inventaire): View
    {
        // Ensure the inventory belongs to the current dossier
        if ($inventaire->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        // Convertir le statut pour l'affichage
        $inventaire->statut_display = $this->statutMappingReverse[$inventaire->statut] ?? 'Planifié';

        // Load details with pagination
        $detailsInventaire = $inventaire->details()
                                        ->with(["user", "immobilisation", "immobilisation.site", "immobilisation.service"]) // Eager load user and related immo
                                        ->orderBy("created_at", "desc")
                                        ->paginate(20); // Paginate details

        // Convertir les statuts constatés pour l'affichage
        foreach ($detailsInventaire as $detail) {
            $detail->statut_constate_display = $this->statutMappingReverse[$detail->statut_constate] ?? $detail->statut_constate;
        }

        return view("inventaires.show", compact("inventaire", "detailsInventaire"));
    }

    /**
     * Show the form for editing the specified inventory session.
     */
    public function edit(Inventaire $inventaire): View
    {
        // Ensure the inventory belongs to the current dossier and is not terminated
        if ($inventaire->dossier_id != $this->getCurrentDossierId() || $inventaire->statut == "termine") {
            abort(403, "Accès non autorisé ou session terminée.");
        }

        // Convertir le statut de la base de données vers la valeur du formulaire
        $inventaire->statut_display = $this->statutMappingReverse[$inventaire->statut] ?? 'Planifié';

        // Passer les options de statut au formulaire
        $statutOptions = [
            'Planifié' => 'Planifié',
            'En cours' => 'En cours',
            'Terminé' => 'Terminé',
            'Annulé' => 'Annulé'
        ];

        return view("inventaires.edit", compact("inventaire", "statutOptions"));
    }

    /**
     * Update the specified inventory session in storage.
     */
    public function update(Request $request, Inventaire $inventaire): RedirectResponse
    {
        // Ensure the inventory belongs to the current dossier and is not terminated
        if ($inventaire->dossier_id != $this->getCurrentDossierId() || $inventaire->statut == "termine") {
            abort(403, "Accès non autorisé ou session terminée.");
        }

        $dossierId = $this->getCurrentDossierId();
        $validatedData = $request->validate([
            "reference" => ["required", "string", "max:255", "unique:inventaires,reference,".$inventaire->id.",id,dossier_id,".$dossierId],
            "date_debut" => ["required", "date"],
            "date_fin" => ["nullable", "date", "after_or_equal:date_debut"],
            "description" => ["nullable", "string"],
            "statut" => ["required", "in:Planifié,En cours,Terminé,Annulé"],
            "site_id" => ["nullable", "exists:sites,id"],
            "service_id" => ["nullable", "exists:services,id"],
        ]);

        // Convertir le statut du formulaire vers la valeur de la base de données
        $dbStatut = 'planifie';
        if ($validatedData["statut"] === 'En cours') {
            $dbStatut = 'en_cours';
        } elseif ($validatedData["statut"] === 'Terminé') {
            $dbStatut = 'termine';
        } elseif ($validatedData["statut"] === 'Annulé') {
            $dbStatut = 'annule';
        }

        // If status is set to termine, set date_fin if not already set
        if ($dbStatut == "termine" && empty($validatedData["date_fin"])) {
            $validatedData["date_fin"] = Carbon::now();
        }

        // Préparer les données à mettre à jour
        $dataToUpdate = $validatedData;
        $dataToUpdate["statut"] = $dbStatut;

        $inventaire->update($dataToUpdate);

        return redirect()->route("inventaires.show", $inventaire)
                         ->with("success", "Session d'inventaire mise à jour avec succès.");
    }

    /**
     * Remove the specified inventory session from storage.
     * (Use with caution, might be better to just mark as cancelled/archived)
     */
    public function destroy(Inventaire $inventaire): RedirectResponse
    {
         // Ensure the inventory belongs to the current dossier
        if ($inventaire->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        // Optional: Add checks, e.g., cannot delete if counts exist or status is 'termine'
        if ($inventaire->details()->exists() || $inventaire->statut == 'termine') {
             return redirect()->route("inventaires.index")
                         ->with("error", "Impossible de supprimer une session terminée ou contenant des comptages.");
        }

        $inventaire->delete();

        return redirect()->route("inventaires.index")
                         ->with("success", "Session d'inventaire supprimée avec succès.");
    }

    /**
     * Show the form to manually add a count.
     */
    public function showAddCountForm(Inventaire $inventaire): View
    {
        if ($inventaire->dossier_id != $this->getCurrentDossierId() || $inventaire->statut == "termine") {
            abort(403, "Accès non autorisé ou session terminée.");
        }

        $dossierId = $this->getCurrentDossierId();
        
        // Récupérer tous les sites et services pour les filtres
        $sites = Site::where('dossier_id', $dossierId)->orderBy('libelle')->get();
        $services = Service::where('dossier_id', $dossierId)->orderBy('libelle')->get();
        
        // Récupérer les immobilisations actives du dossier
        $query = Immobilisation::where('dossier_id', $dossierId)
            ->where('statut', '!=', 'Cédé')
            ->where('statut', '!=', 'Rebut')
            ->with(['site', 'service']);
        
        // Appliquer les filtres si présents
        $siteId = request('site_id');
        $serviceId = request('service_id');
        
        if ($siteId) {
            $query->where('site_id', $siteId);
        }
        
        if ($serviceId) {
            $query->where('service_id', $serviceId);
        }
        
        $immobilisations = $query->orderBy('designation')->paginate(15);
        
        // Récupérer les détails d'inventaire existants pour ces immobilisations
        $immobilisationIds = $immobilisations->pluck('id')->toArray();
        $detailsExistants = $inventaire->details()
            ->whereIn('immobilisation_id', $immobilisationIds)
            ->get()
            ->keyBy('immobilisation_id');
            
        // Préparer les options de statut pour le formulaire
        $statutOptions = [
            'Trouvé' => 'Trouvé',
            'Non trouvé en base' => 'Non trouvé en base',
            'Non trouvé physiquement' => 'Non trouvé physiquement',
            'Écart de localisation' => 'Écart de localisation',
            'Écart d\'état' => 'Écart d\'état',
            'Endommagé' => 'Endommagé'
        ];
        
        // Préparer le mapping inverse pour l'affichage
        $statutMappingReverse = $this->statutMappingReverse;
        
        return view("inventaires.add_count", compact("inventaire", "immobilisations", "sites", "services", "siteId", "serviceId", "detailsExistants", "statutOptions", "statutMappingReverse"));
    }

    /**
     * Store a manually added count.
     */
    public function storeCount(Request $request, Inventaire $inventaire): RedirectResponse
    {
        if ($inventaire->dossier_id != $this->getCurrentDossierId() || $inventaire->statut == "termine") {
            abort(403, "Accès non autorisé ou session terminée.");
        }

        $validatedData = $request->validate([
            "code_barre_scan" => ["required", "string", "max:255"],
            "statut_constate" => ["required", "string", "in:" . implode(',', array_keys($this->statutMapping))],
            "commentaire" => ["nullable", "string", "max:1000"],
            "site_scan_id" => ["nullable", "exists:sites,id"],
            "service_scan_id" => ["nullable", "exists:services,id"],
        ]);

        // Convertir le statut constaté pour la base de données
        $dbStatutConstate = $this->statutMapping[$validatedData["statut_constate"]] ?? 'trouve';

        // Find corresponding immobilisation
        $immobilisation = Immobilisation::where("dossier_id", $inventaire->dossier_id)
                                        ->where("code_barre", $validatedData["code_barre_scan"])
                                        ->first();

        InventaireDetail::create([
            "inventaire_id" => $inventaire->id,
            "immobilisation_id" => $immobilisation ? $immobilisation->id : null,
            "user_id" => Auth::id(),
            "code_barre_scan" => $validatedData["code_barre_scan"],
            "statut_constate" => $dbStatutConstate,
            "commentaire" => $validatedData["commentaire"],
            "site_scan_id" => $validatedData["site_scan_id"] ?? null,
            "service_scan_id" => $validatedData["service_scan_id"] ?? null,
            "date_scan" => Carbon::now(),
        ]);

        return redirect()->route("inventaires.show", $inventaire)
                         ->with("success", "Comptage enregistré avec succès.");
    }

    /**
     * Update a count via AJAX.
     */
    public function updateCount(Request $request, Inventaire $inventaire)
    {
        if ($inventaire->dossier_id != $this->getCurrentDossierId() || $inventaire->statut == "termine") {
            return response()->json(['success' => false, 'message' => "Accès non autorisé ou session terminée."], 403);
        }

        try {
            $validatedData = $request->validate([
                "immobilisation_id" => ["required", "exists:immobilisations,id"],
                "statut_constate" => ["required", "string"],
                "commentaire" => ["nullable", "string", "max:1000"],
            ]);

            // Si le statut est "Non compté", supprimer le détail s'il existe
            if ($validatedData["statut_constate"] === "Non compté") {
                $detail = $inventaire->details()
                    ->where('immobilisation_id', $validatedData["immobilisation_id"])
                    ->first();
                    
                if ($detail) {
                    $detail->delete();
                    return response()->json([
                        'success' => true, 
                        'message' => "Comptage supprimé avec succès.",
                        'statut_display' => "Non compté"
                    ]);
                }
                
                return response()->json([
                    'success' => true, 
                    'message' => "Aucun changement nécessaire.",
                    'statut_display' => "Non compté"
                ]);
            }

            // Vérifier si le statut est valide et le convertir pour la base de données
            // CORRECTION: Vérifier si le statut est une valeur du mapping (et non une clé)
            if (!in_array($validatedData["statut_constate"], array_keys($this->statutMapping)) && $validatedData["statut_constate"] !== "Non compté") {
                return response()->json(['success' => false, 'message' => "Statut invalide: " . $validatedData["statut_constate"]], 422);
            }

            // Convertir le statut constaté pour la base de données
            $dbStatutConstate = $this->statutMapping[$validatedData["statut_constate"]] ?? 'trouve';

            // Vérifier si l'immobilisation appartient au dossier courant
            $immobilisation = Immobilisation::where('id', $validatedData["immobilisation_id"])
                ->where('dossier_id', $this->getCurrentDossierId())
                ->first();

            if (!$immobilisation) {
                return response()->json(['success' => false, 'message' => "Immobilisation non trouvée ou non autorisée."], 404);
            }

            // Vérifier si un détail existe déjà pour cette immobilisation dans cet inventaire
            $detail = $inventaire->details()
                ->where('immobilisation_id', $validatedData["immobilisation_id"])
                ->first();

            if ($detail) {
                // Mettre à jour le détail existant
                $detail->update([
                    "statut_constate" => $dbStatutConstate,
                    "commentaire" => $validatedData["commentaire"],
                    "user_id" => Auth::id(),
                    "date_scan" => Carbon::now(),
                ]);
            } else {
                // Créer un nouveau détail
                InventaireDetail::create([
                    "inventaire_id" => $inventaire->id,
                    "immobilisation_id" => $validatedData["immobilisation_id"],
                    "user_id" => Auth::id(),
                    "code_barre_scan" => $immobilisation->code_barre,
                    "statut_constate" => $dbStatutConstate,
                    "commentaire" => $validatedData["commentaire"],
                    "site_scan_id" => $immobilisation->site_id,
                    "service_scan_id" => $immobilisation->service_id,
                    "date_scan" => Carbon::now(),
                ]);
            }

            return response()->json([
                'success' => true, 
                'message' => "Comptage mis à jour avec succès.",
                'statut_display' => $validatedData["statut_constate"]
            ]);
        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            \Log::error('Erreur dans updateCount: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false, 
                'message' => "Une erreur est survenue: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form to import counts.
     */
    public function showImportCountsForm(Inventaire $inventaire): View
    {
        if ($inventaire->dossier_id != $this->getCurrentDossierId() || $inventaire->statut == "termine") {
            abort(403, "Accès non autorisé ou session terminée.");
        }

        $dossierId = $this->getCurrentDossierId();
        
        // Récupérer tous les sites et services pour les filtres
        $sites = Site::where('dossier_id', $dossierId)->orderBy('libelle')->get();
        $services = Service::where('dossier_id', $dossierId)->orderBy('libelle')->get();
        
        // Récupérer les immobilisations actives du dossier
        $query = Immobilisation::where('dossier_id', $dossierId)
            ->where('statut', '!=', 'Cédé')
            ->where('statut', '!=', 'Rebut')
            ->with(['site', 'service']);
        
        // Appliquer les filtres si présents
        $siteId = request('site_id');
        $serviceId = request('service_id');
        
        if ($siteId) {
            $query->where('site_id', $siteId);
        }
        
        if ($serviceId) {
            $query->where('service_id', $serviceId);
        }
        
        $immobilisations = $query->orderBy('designation')->paginate(15);
        
        // Préparer le mapping inverse pour l'affichage
        $statutMappingReverse = $this->statutMappingReverse;
        
        return view("inventaires.import_counts", compact("inventaire", "immobilisations", "sites", "services", "siteId", "serviceId", "statutMappingReverse"));
    }

    /**
     * Handle the import of counts.
     */
    public function handleImportCounts(Request $request, Inventaire $inventaire): RedirectResponse
    {
        if ($inventaire->dossier_id != $this->getCurrentDossierId() || $inventaire->statut == "termine") {
            abort(403, "Accès non autorisé ou session terminée.");
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        
        $handle = fopen($path, 'r');
        $header = fgetcsv($handle, 1000, ',');
        
        // Vérifier que le fichier a le bon format
        if (!in_array('immobilisation_id', $header) || !in_array('statut_constate', $header)) {
            return redirect()->back()->with('error', 'Format de fichier incorrect. Veuillez utiliser le modèle fourni.');
        }
        
        $headerMap = array_flip($header);
        $importCount = 0;
        $errorCount = 0;
        
        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $immobilisationId = $data[$headerMap['immobilisation_id']] ?? null;
                $statutConstate = $data[$headerMap['statut_constate']] ?? null;
                $commentaire = $data[$headerMap['commentaire']] ?? null;
                
                if (!$immobilisationId || !$statutConstate) {
                    $errorCount++;
                    continue;
                }
                
                // Vérifier si l'immobilisation existe et appartient au dossier courant
                $immobilisation = Immobilisation::where('id', $immobilisationId)
                    ->where('dossier_id', $this->getCurrentDossierId())
                    ->first();
                
                if (!$immobilisation) {
                    $errorCount++;
                    continue;
                }
                
                // Convertir le statut constaté pour la base de données
                if ($statutConstate === "Non compté") {
                    // Supprimer le détail s'il existe
                    $inventaire->details()
                        ->where('immobilisation_id', $immobilisationId)
                        ->delete();
                } else {
                    // Vérifier si le statut est valide
                    if (!array_key_exists($statutConstate, $this->statutMapping)) {
                        $errorCount++;
                        continue;
                    }
                    
                    $dbStatutConstate = $this->statutMapping[$statutConstate];
                    
                    // Vérifier si un détail existe déjà pour cette immobilisation
                    $detail = $inventaire->details()
                        ->where('immobilisation_id', $immobilisationId)
                        ->first();
                    
                    if ($detail) {
                        // Mettre à jour le détail existant
                        $detail->update([
                            "statut_constate" => $dbStatutConstate,
                            "commentaire" => $commentaire,
                            "user_id" => Auth::id(),
                            "date_scan" => Carbon::now(),
                        ]);
                    } else {
                        // Créer un nouveau détail
                        InventaireDetail::create([
                            "inventaire_id" => $inventaire->id,
                            "immobilisation_id" => $immobilisationId,
                            "user_id" => Auth::id(),
                            "code_barre_scan" => $immobilisation->code_barre,
                            "statut_constate" => $dbStatutConstate,
                            "commentaire" => $commentaire,
                            "site_scan_id" => $immobilisation->site_id,
                            "service_scan_id" => $immobilisation->service_id,
                            "date_scan" => Carbon::now(),
                        ]);
                    }
                }
                
                $importCount++;
            }
            
            DB::commit();
            
            return redirect()->route('inventaires.show', $inventaire)
                ->with('success', "Import terminé avec succès. $importCount comptages importés. $errorCount erreurs.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'import: ' . $e->getMessage());
        } finally {
            fclose($handle);
        }
    }

    /**
     * Export immobilisations to CSV for inventory.
     * Cette méthode est utilisée pour l'export CSV des immobilisations.
     */
    public function exportImmobilisations(Inventaire $inventaire)
    {
        if ($inventaire->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        $dossierId = $this->getCurrentDossierId();
        
        // Récupérer les immobilisations actives du dossier
        $query = Immobilisation::where('dossier_id', $dossierId)
            ->where('statut', '!=', 'Cédé')
            ->where('statut', '!=', 'Rebut')
            ->with(['site', 'service']);
        
        // Appliquer les filtres si présents
        $siteId = request('site_id');
        $serviceId = request('service_id');
        
        if ($siteId) {
            $query->where('site_id', $siteId);
        }
        
        if ($serviceId) {
            $query->where('service_id', $serviceId);
        }
        
        $immobilisations = $query->orderBy('designation')->get();
        
        // Récupérer les détails d'inventaire existants pour ces immobilisations
        $immobilisationIds = $immobilisations->pluck('id')->toArray();
        $detailsExistants = $inventaire->details()
            ->whereIn('immobilisation_id', $immobilisationIds)
            ->get()
            ->keyBy('immobilisation_id');
        
        // Préparer le fichier CSV
        $filename = 'inventaire_' . $inventaire->reference . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($immobilisations, $detailsExistants) {
            $file = fopen('php://output', 'w');
            
            // Ajouter BOM pour UTF-8
            fputs($file, "\xEF\xBB\xBF");
            
            // En-têtes
            fputcsv($file, ['immobilisation_id', 'code_barre', 'designation', 'description', 'service', 'site', 'statut_constate', 'commentaire'], ';');
            
            foreach ($immobilisations as $immobilisation) {
                $detail = $detailsExistants[$immobilisation->id] ?? null;
                $statutConstate = $detail ? ($this->statutMappingReverse[$detail->statut_constate] ?? $detail->statut_constate) : 'Non compté';
                $commentaire = $detail ? $detail->commentaire : '';
                
                fputcsv($file, [
                    $immobilisation->id,
                    $immobilisation->code_barre ?? '',
                    $immobilisation->designation ?? '',
                    $immobilisation->description ?? '',
                    $immobilisation->service ? $immobilisation->service->libelle : '',
                    $immobilisation->site ? $immobilisation->site->libelle : '',
                    $statutConstate,
                    $commentaire
                ], ';');
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Alias pour exportImmobilisations pour compatibilité avec les routes existantes.
     */
    public function exportImmobilisationsCSV(Inventaire $inventaire)
    {
        return $this->exportImmobilisations($inventaire);
    }

    /**
     * Export resultats to CSV.
     */
    public function exportResultatsCSV(Inventaire $inventaire)
    {
        if ($inventaire->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        // Récupérer les immobilisations manquantes
        $immobilisationsManquantes = $inventaire->details()
            ->whereNotNull("immobilisation_id")
            ->where('statut_constate', 'non_trouve_physique')
            ->with(['immobilisation', 'immobilisation.site', 'immobilisation.service'])
            ->get();
        
        // Récupérer les immobilisations trouvées
        $immobilisationsTrouvees = $inventaire->details()
            ->whereNotNull("immobilisation_id")
            ->where('statut_constate', 'trouve')
            ->with(['immobilisation', 'immobilisation.site', 'immobilisation.service', 'user'])
            ->get();
        
        // Récupérer les codes-barres scannés mais non trouvés dans la base
        $codesBarresNonTrouves = $inventaire->details()
            ->whereNull("immobilisation_id")
            ->where('statut_constate', 'non_trouve_base')
            ->get();
        
        // Préparer le fichier CSV
        $filename = 'resultats_inventaire_' . $inventaire->reference . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($immobilisationsManquantes, $immobilisationsTrouvees, $codesBarresNonTrouves) {
            $file = fopen('php://output', 'w');
            
            // Ajouter BOM pour UTF-8
            fputs($file, "\xEF\xBB\xBF");
            
            // Section 1: Immobilisations manquantes
            fputcsv($file, ['IMMOBILISATIONS MANQUANTES'], ';');
            fputcsv($file, ['ID', 'Code Barre', 'Désignation', 'Description', 'Service', 'Site'], ';');
            
            foreach ($immobilisationsManquantes as $detail) {
                $immobilisation = $detail->immobilisation;
                if ($immobilisation) {
                    fputcsv($file, [
                        $immobilisation->id,
                        $immobilisation->code_barre ?? '',
                        $immobilisation->designation ?? '',
                        $immobilisation->description ?? '',
                        $immobilisation->service ? $immobilisation->service->libelle : '',
                        $immobilisation->site ? $immobilisation->site->libelle : '',
                        $immobilisation->statut ?? ''
                    ], ';');
                }
            }
            
            // Section 2: Immobilisations trouvées
            fputcsv($file, []);
            fputcsv($file, ['IMMOBILISATIONS TROUVÉES'], ';');
            fputcsv($file, ['ID', 'Code Barre', 'Désignation', 'Description', 'Service', 'Site', 'Date Comptage', 'Utilisateur', 'Commentaire'], ';');
            
            foreach ($immobilisationsTrouvees as $detail) {
                $immobilisation = $detail->immobilisation;
                if ($immobilisation) {
                    fputcsv($file, [
                        $immobilisation->id,
                        $immobilisation->code_barre ?? '',
                        $immobilisation->designation ?? '',
                        $immobilisation->description ?? '',
                        $immobilisation->service ? $immobilisation->service->libelle : '',
                        $immobilisation->site ? $immobilisation->site->libelle : '',
                        $detail->date_scan ? Carbon::parse($detail->date_scan)->format('d/m/Y H:i') : '',
                        $detail->user ? $detail->user->name : '',
                        $detail->commentaire ?? ''
                    ], ';');
                }
            }
            
            // Section 3: Codes-barres non trouvés
            fputcsv($file, []);
            fputcsv($file, ['CODES-BARRES NON TROUVÉS DANS LA BASE'], ';');
            fputcsv($file, ['Code Barre Scanné', 'Date Scan', 'Utilisateur', 'Commentaire'], ';');
            
            foreach ($codesBarresNonTrouves as $detail) {
                fputcsv($file, [
                    $detail->code_barre_scan ?? '',
                    $detail->date_scan ? Carbon::parse($detail->date_scan)->format('d/m/Y H:i') : '',
                    $detail->user ? $detail->user->name : '',
                    $detail->commentaire ?? ''
                ], ';');
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the results of the inventory.
     */
    public function showResults(Inventaire $inventaire): View
    {
        if ($inventaire->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        $dossierId = $this->getCurrentDossierId();
        
        // Récupérer tous les sites et services pour les filtres
        $sites = Site::where('dossier_id', $dossierId)->orderBy('libelle')->get();
        $services = Service::where('dossier_id', $dossierId)->orderBy('libelle')->get();
        
        // Récupérer les filtres
        $siteId = request('site_id');
        $serviceId = request('service_id');

        // Récupérer les statistiques
        $totalImmobilisations = Immobilisation::where('dossier_id', $this->getCurrentDossierId())
            ->where('statut', '!=', 'Cédé')
            ->where('statut', '!=', 'Rebut')
            ->count();
        
        $totalTrouvees = $inventaire->details()
            ->whereNotNull('immobilisation_id')
            ->where('statut_constate', 'trouve')
            ->count();
        
        $totalManquantes = $totalImmobilisations - $totalTrouvees;
        
        // Préparer les statistiques pour la vue
        $stats = [
            'attendues' => $totalImmobilisations,
            'trouvees' => $totalTrouvees,
            'manquantes' => $totalManquantes
        ];
        
        // Récupérer les immobilisations manquantes
        $immobilisationsManquantesQuery = Immobilisation::where('dossier_id', $dossierId)
            ->where('statut', '!=', 'Cédé')
            ->where('statut', '!=', 'Rebut')
            ->whereNotIn('id', function($query) use ($inventaire) {
                $query->select('immobilisation_id')
                    ->from('inventaire_details')
                    ->where('inventaire_id', $inventaire->id)
                    ->where('statut_constate', 'trouve');
            })
            ->with(['site', 'service']);
        
        // Appliquer les filtres si présents
        if ($siteId) {
            $immobilisationsManquantesQuery->where('site_id', $siteId);
        }
        
        if ($serviceId) {
            $immobilisationsManquantesQuery->where('service_id', $serviceId);
        }
        
        $immobilisationsManquantes = $immobilisationsManquantesQuery->paginate(10, ['*'], 'manquantes_page');
        
        // Récupérer les immobilisations trouvées
        $detailsTrouvesQuery = $inventaire->details()
            ->whereNotNull("immobilisation_id")
            ->where('statut_constate', 'trouve')
            ->with(['immobilisation', 'immobilisation.site', 'immobilisation.service', 'user']);
        
        // Appliquer les filtres si présents
        if ($siteId || $serviceId) {
            $detailsTrouvesQuery->whereHas('immobilisation', function($query) use ($siteId, $serviceId) {
                if ($siteId) {
                    $query->where('site_id', $siteId);
                }
                
                if ($serviceId) {
                    $query->where('service_id', $serviceId);
                }
            });
        }
        
        $detailsTrouves = $detailsTrouvesQuery->paginate(10, ['*'], 'trouves_page');
        
        // Récupérer les codes-barres scannés mais non trouvés dans la base
        $detailsNonReconnusQuery = $inventaire->details()
            ->whereNull("immobilisation_id")
            ->where('statut_constate', 'non_trouve_base');
        
        $detailsNonReconnus = $detailsNonReconnusQuery->paginate(10, ['*'], 'non_reconnus_page');
        
        return view("inventaires.results", compact(
            "inventaire", 
            "sites",
            "services",
            "siteId",
            "serviceId",
            "stats",
            "immobilisationsManquantes", 
            "detailsTrouves", 
            "detailsNonReconnus"
        ));
    }
}
