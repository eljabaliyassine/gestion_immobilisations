<?php

namespace App\Http\Controllers;

use App\Models\Immobilisation;
use App\Models\Famille;
use App\Models\Site;
use App\Models\Service;
use App\Models\Fournisseur;
use App\Models\CompteCompta;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImmobilisationsImport;
use App\Exports\ImmobilisationsExport;
use Illuminate\Support\Facades\Log;

class ImmobilisationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('dossier.selected');
    }

    /**
     * Récupère l'ID du dossier courant depuis la session.
     *
     * @return int|null
     */
    protected function getCurrentDossierId()
    {
        return session('current_dossier_id') ?? session('dossier_id');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $dossierId = $this->getCurrentDossierId();
        $query = Immobilisation::where("dossier_id", $dossierId)->with(["famille", "site", "service"]);

        // Apply filters
        if ($request->filled("filter_code_barre")) {
            $query->where("code_barre", "like", "%" . $request->input("filter_code_barre") . "%");
        }
        if ($request->filled("filter_designation")) {
            $query->where("designation", "like", "%" . $request->input("filter_designation") . "%");
        }
        if ($request->filled("filter_famille_id")) {
            $query->where("famille_id", $request->input("filter_famille_id"));
        }
        if ($request->filled("filter_site_id")) {
            $query->where("site_id", $request->input("filter_site_id"));
        }
        if ($request->filled("filter_service_id")) {
            $query->where("service_id", $request->input("filter_service_id"));
        }

        $immobilisations = $query->latest()->paginate(15);

        // Data for filters - FILTRÉ PAR DOSSIER COURANT
        $familles = Famille::where("dossier_id", $dossierId)->orderBy("libelle")->get();
        $sites = Site::where("dossier_id", $dossierId)->orderBy("libelle")->get();
        $services = Service::where("dossier_id", $dossierId)->orderBy("libelle")->get();

        return view("immobilisations.index", compact("immobilisations", "familles", "sites", "services"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $dossierId = $this->getCurrentDossierId();

        // FILTRAGE PAR DOSSIER COURANT UNIQUEMENT
        $familles = Famille::where("dossier_id", $dossierId)->orderBy("libelle")->get();
        $sites = Site::where("dossier_id", $dossierId)->orderBy("libelle")->get();
        $fournisseurs = Fournisseur::where("dossier_id", $dossierId)->orderBy("nom")->get();
        $comptescompta = CompteCompta::where("dossier_id", $dossierId)->orderBy("numero")->get();

        // Services seront chargés dynamiquement via JavaScript
        $services = collect(); // Collection vide, sera remplie via AJAX

        return view("immobilisations.create", compact("familles", "sites", "fournisseurs", "services", "comptescompta"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $dossierId = $this->getCurrentDossierId();

        $validatedData = $request->validate([
            "code_barre" => ["nullable", "string", "max:100", Rule::unique("immobilisations")->where("dossier_id", $dossierId)],
            "designation" => ["required", "string", "max:255"],
            "description" => ["nullable", "string"],
            "famille_id" => ["required", "exists:familles,id,dossier_id,".$dossierId],
            "site_id" => ["required", "exists:sites,id,dossier_id,".$dossierId],
            "service_id" => ["required", Rule::exists("services", "id")->where(function ($query) use ($request, $dossierId) {
                $query->where("dossier_id", $dossierId)
                      ->where("site_id", $request->input("site_id"));
            })],
            "date_acquisition" => ["required", "date"],
            "date_mise_service" => ["nullable", "date", "after_or_equal:date_acquisition"],
            "valeur_acquisition" => ["required", "numeric", "min:0"],
            "tva_deductible" => ["nullable", "numeric", "min:0"],
            "comptecompta_tva_id" => ["nullable", "exists:comptescompta,id,dossier_id,".$dossierId],
            "base_amortissement" => ["required", "numeric", "min:0"],
            "statut" => ["required", Rule::in(["En service", "En stock", "En réparation", "Cédé", "Rebut"])],
            "fournisseur_id" => ["nullable", "exists:fournisseurs,id,dossier_id,".$dossierId],
            "numero_facture" => ["nullable", "string", "max:100"],
            "numero_serie" => ["nullable", "string", "max:100"],
            "emplacement" => ["nullable", "string", "max:255"],
            "photo" => ["nullable", "image", "max:2048"],
            "document_path" => ["nullable", "file", "mimes:pdf,doc,docx", "max:10240"],
        ]);

        $validatedData["dossier_id"] = $dossierId;

        // Récupérer les valeurs de la famille sélectionnée pour les champs hérités
        $famille = Famille::findOrFail($request->input('famille_id'));
        $validatedData["comptecompta_immobilisation_id"] = $famille->comptecompta_immobilisation_id;
        $validatedData["comptecompta_amortissement_id"] = $famille->comptecompta_amortissement_id;
        $validatedData["comptecompta_dotation_id"] = $famille->comptecompta_dotation_id;
        $validatedData["duree_amortissement"] = $famille->duree_amortissement_par_defaut;
        $validatedData["methode_amortissement"] = $famille->methode_amortissement_par_defaut;

        // Mapper le statut "En service" à "actif" pour la base de données
        if ($validatedData["statut"] === "En service") {
            $validatedData["statut"] = "actif";
        }

        // Handle photo upload
        if ($request->hasFile("photo")) {
            $validatedData["photo_path"] = $request->file("photo")->store("immobilisations_photos/dossier_" . $dossierId, "public");
        }

        // Handle document upload
        if ($request->hasFile("document_path")) {
            $validatedData["document_path"] = $request->file("document_path")->store("immobilisations_documents/dossier_" . $dossierId, "public");
        }

        $immobilisation = Immobilisation::create($validatedData);

        return redirect()->route("immobilisations.index")
                         ->with("success", "Immobilisation créée avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Immobilisation $immobilisation): View
    {
        if ($immobilisation->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        $immobilisation->load([
            "famille",
            "site",
            "service",
            "fournisseur",
            "planAmortissements",
            "mouvements",
            "maintenances",
            "contrats",
            "compteImmobilisation",
            "compteAmortissement",
            "compteDotation"
        ]);

        return view("immobilisations.show", compact("immobilisation"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Immobilisation $immobilisation): View
    {
        if ($immobilisation->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        $dossierId = $this->getCurrentDossierId();

        // FILTRAGE PAR DOSSIER COURANT UNIQUEMENT
        $familles = Famille::where("dossier_id", $dossierId)->orderBy("libelle")->get();
        $sites = Site::where("dossier_id", $dossierId)->orderBy("libelle")->get();
        $fournisseurs = Fournisseur::where("dossier_id", $dossierId)->orderBy("nom")->get();
        $comptescompta = CompteCompta::where("dossier_id", $dossierId)->orderBy("numero")->get();

        // Charger les services du site actuel de l'immobilisation
        $services = Service::where("dossier_id", $dossierId)
                          ->where("site_id", $immobilisation->site_id)
                          ->orderBy("libelle")
                          ->get();

        // Mapper le statut "actif" à "En service" pour l'affichage
        if ($immobilisation->statut === "actif") {
            $immobilisation->statut = "En service";
        }

        return view("immobilisations.edit", compact("immobilisation", "familles", "sites", "fournisseurs", "services", "comptescompta"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Immobilisation $immobilisation): RedirectResponse
    {
        if ($immobilisation->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        $dossierId = $this->getCurrentDossierId();

        $validatedData = $request->validate([
            "code_barre" => ["nullable", "string", "max:100", Rule::unique("immobilisations")->where("dossier_id", $dossierId)->ignore($immobilisation->id)],
            "designation" => ["required", "string", "max:255"],
            "description" => ["nullable", "string"],
            "famille_id" => ["required", "exists:familles,id,dossier_id,".$dossierId],
            "site_id" => ["required", "exists:sites,id,dossier_id,".$dossierId],
            "service_id" => ["required", Rule::exists("services", "id")->where(function ($query) use ($request, $dossierId) {
                $query->where("dossier_id", $dossierId)
                      ->where("site_id", $request->input("site_id"));
            })],
            "date_acquisition" => ["required", "date"],
            "date_mise_service" => ["nullable", "date", "after_or_equal:date_acquisition"],
            "valeur_acquisition" => ["required", "numeric", "min:0"],
            "tva_deductible" => ["nullable", "numeric", "min:0"],
            "comptecompta_tva_id" => ["nullable", "exists:comptescompta,id,dossier_id,".$dossierId],
            "base_amortissement" => ["required", "numeric", "min:0"],
            "statut" => ["required", Rule::in(["En service", "En stock", "En réparation", "Cédé", "Rebut"])],
            "fournisseur_id" => ["nullable", "exists:fournisseurs,id,dossier_id,".$dossierId],
            "numero_facture" => ["nullable", "string", "max:100"],
            "numero_serie" => ["nullable", "string", "max:100"],
            "emplacement" => ["nullable", "string", "max:255"],
            "photo" => ["nullable", "image", "max:2048"],
            "document_path" => ["nullable", "file", "mimes:pdf,doc,docx", "max:10240"],
        ]);

        // ACTUALISATION DES INFORMATIONS HÉRITÉES SI LA FAMILLE A CHANGÉ
        if ($immobilisation->famille_id != $request->input('famille_id')) {
            $famille = Famille::findOrFail($request->input('famille_id'));
            $validatedData["comptecompta_immobilisation_id"] = $famille->comptecompta_immobilisation_id;
            $validatedData["comptecompta_amortissement_id"] = $famille->comptecompta_amortissement_id;
            $validatedData["comptecompta_dotation_id"] = $famille->comptecompta_dotation_id;
            $validatedData["duree_amortissement"] = $famille->duree_amortissement_par_defaut;
            $validatedData["methode_amortissement"] = $famille->methode_amortissement_par_defaut;
        }

        // Mapper le statut "En service" à "actif" pour la base de données
        if ($validatedData["statut"] === "En service") {
            $validatedData["statut"] = "actif";
        }

        // Handle photo upload and deletion of old photo
        if ($request->hasFile("photo")) {
            if ($immobilisation->photo_path) {
                Storage::disk("public")->delete($immobilisation->photo_path);
            }
            $validatedData["photo_path"] = $request->file("photo")->store("immobilisations_photos/dossier_" . $dossierId, "public");
        }

        // Handle document upload and deletion of old document
        if ($request->hasFile("document_path")) {
            if ($immobilisation->document_path) {
                Storage::disk("public")->delete($immobilisation->document_path);
            }
            $validatedData["document_path"] = $request->file("document_path")->store("immobilisations_documents/dossier_" . $dossierId, "public");
        }

        $immobilisation->update($validatedData);

        return redirect()->route("immobilisations.index")
                         ->with("success", "Immobilisation mise à jour avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Immobilisation $immobilisation): RedirectResponse
    {
        if ($immobilisation->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        try {
            // Check for related data that might prevent deletion
            if ($immobilisation->mouvements()->exists() ||
                $immobilisation->planAmortissements()->exists() ||
                $immobilisation->contrats()->exists() ||
                $immobilisation->maintenances()->exists()) {
                return redirect()->route("immobilisations.index")
                               ->with("error", "Impossible de supprimer l'immobilisation car elle a un historique (mouvements, amortissements, contrats, maintenances). Changez son statut à \"Rebut\" ou \"Cédé\".");
            }

            // Delete photo if it exists
            if ($immobilisation->photo_path) {
                Storage::disk("public")->delete($immobilisation->photo_path);
            }

            // Delete document if it exists
            if ($immobilisation->document_path) {
                Storage::disk("public")->delete($immobilisation->document_path);
            }

            $immobilisation->delete();

            return redirect()->route("immobilisations.index")
                             ->with("success", "Immobilisation supprimée avec succès.");

        } catch (\Exception $e) {
            logger()->error("Error deleting immobilisation {$immobilisation->id}: ".$e->getMessage());
            return redirect()->route("immobilisations.index")
                             ->with("error", "Erreur lors de la suppression de l'immobilisation.");
        }
    }

    /**
     * AJAX: Récupérer les services d'un site spécifique
     * SOLUTION ALTERNATIVE: Utilise des routes web normales
     */
    public function ajaxGetServicesBySite(Request $request)
    {
        try {
            $siteId = $request->input('site_id');
            $dossierId = $this->getCurrentDossierId();

            if (!$siteId || !$dossierId) {
                return response()->json(['error' => 'Paramètres manquants'], 400);
            }

            // Vérifier que le site existe et appartient au dossier
            $site = Site::where('id', $siteId)->where('dossier_id', $dossierId)->first();
            if (!$site) {
                return response()->json(['error' => 'Site non trouvé'], 404);
            }

            $services = Service::where('dossier_id', $dossierId)
                              ->where('site_id', $siteId)
                              ->orderBy('libelle')
                              ->get(['id', 'libelle']);

            return response()->json($services);

        } catch (\Exception $e) {
            Log::error("Erreur AJAX services par site", [
                'site_id' => $request->input('site_id'),
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    /**
     * AJAX: Récupérer les informations d'une famille
     * SOLUTION ALTERNATIVE: Utilise des routes web normales
     */
    public function ajaxGetFamilleInfo(Request $request)
    {
        try {
            $familleId = $request->input('famille_id');
            $dossierId = $this->getCurrentDossierId();

            if (!$familleId || !$dossierId) {
                return response()->json(['error' => 'Paramètres manquants'], 400);
            }

            // Récupérer la famille avec ses relations
            $famille = Famille::with(['compteImmobilisation', 'compteAmortissement', 'compteDotation'])
                              ->where('dossier_id', $dossierId)
                              ->where('id', $familleId)
                              ->first();

            if (!$famille) {
                return response()->json(['error' => 'Famille non trouvée'], 404);
            }

            $response = [
                'comptecompta_immobilisation' => $famille->compteImmobilisation ?
                    $famille->compteImmobilisation->numero . ' - ' . $famille->compteImmobilisation->libelle : '',
                'comptecompta_amortissement' => $famille->compteAmortissement ?
                    $famille->compteAmortissement->numero . ' - ' . $famille->compteAmortissement->libelle : '',
                'comptecompta_dotation' => $famille->compteDotation ?
                    $famille->compteDotation->numero . ' - ' . $famille->compteDotation->libelle : '',
                'duree_amortissement_par_defaut' => $famille->duree_amortissement_par_defaut ?
                    $famille->duree_amortissement_par_defaut . ' ans' : '',
                'methode_amortissement_par_defaut' => $famille->methode_amortissement_par_defaut ?
                    ucfirst($famille->methode_amortissement_par_defaut) : ''
            ];

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error("Erreur AJAX infos famille", [
                'famille_id' => $request->input('famille_id'),
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    /**
     * Import immobilisations from Excel file
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            $dossierId = $this->getCurrentDossierId();
            Excel::import(new ImmobilisationsImport($dossierId), $request->file('file'));

            return redirect()->route('immobilisations.index')
                           ->with('success', 'Immobilisations importées avec succès.');
        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());
            return redirect()->route('immobilisations.index')
                           ->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage());
        }
    }

    /**
     * Export immobilisations to Excel file
     */
    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $dossierId = $this->getCurrentDossierId();
        $fileName = 'immobilisations_dossier_' . $dossierId . '_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new ImmobilisationsExport($dossierId), $fileName);
    }
}

