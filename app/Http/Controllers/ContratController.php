<?php

namespace App\Http\Controllers;
use App\Models\Contrat;
use App\Models\DetailCreditBail;
use App\Models\EcheanceCreditBail;
use App\Models\Immobilisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ContratController extends Controller
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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dossier_id = $this->getCurrentDossierId();
        $contrats = Contrat::where('dossier_id', $dossier_id)
                          ->orderBy('date_debut', 'desc')
                          ->paginate(15);

        return view('contrats.index', compact('contrats'));
    }

    /**
     * Exporte la liste des contrats au format CSV.
     *
     */
    public function exportCsv()
    {
        $dossier_id = $this->getCurrentDossierId();
        $contrats = Contrat::where('dossier_id', $dossier_id)
                          ->orderBy('date_debut', 'desc')
                          ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="contrats_' . date('Y-m-d_His') . '.csv"',
        ];

        $callback = function() use ($contrats) {
            $file = fopen('php://output', 'w');
            // BOM UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // En-têtes
            fputcsv($file, [
                'ID',
                'Référence',
                'Type',
                'Prestataire',
                'Fournisseur',
                'Date début',
                'Date fin',
                'Description',
                'Montant périodique',
                'Périodicité',
                'Date prochaine échéance',
                'Statut',
                'Nombre d\'immobilisations',
                'Créé le',
                'Modifié le'
            ], ';');

            // Données
            foreach ($contrats as $contrat) {
                fputcsv($file, [
                    $contrat->id,
                    $contrat->reference,
                    $contrat->type,
                    $contrat->prestataire ? $contrat->prestataire->nom : '',
                    $contrat->fournisseur ? $contrat->fournisseur->nom : '',
                    $contrat->date_debut ? $contrat->date_debut->format('d/m/Y') : '',
                    $contrat->date_fin ? $contrat->date_fin->format('d/m/Y') : '',
                    $contrat->description,
                    $contrat->montant_periodique,
                    $contrat->periodicite,
                    $contrat->date_prochaine_echeance ? $contrat->date_prochaine_echeance->format('d/m/Y') : '',
                    $contrat->statut,
                    $contrat->immobilisations()->count(),
                    $contrat->created_at ? $contrat->created_at->format('d/m/Y H:i') : '',
                    $contrat->updated_at ? $contrat->updated_at->format('d/m/Y H:i') : ''
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dossier_id = $this->getCurrentDossierId();
        $fournisseurs = \App\Models\Fournisseur::where('dossier_id', $dossier_id)->orderBy('nom')->get();
        $prestataires = \App\Models\Prestataire::where('dossier_id', $dossier_id)->orderBy('nom')->get();

        return view('contrats.create', compact('fournisseurs', 'prestataires'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'reference' => 'required|string|max:50',
            'type' => 'required|in:maintenance,location,leasing,autre',
            'prestataire_id' => 'nullable|exists:prestataires,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'description' => 'nullable|string',
            'montant_periodique' => 'required|numeric|min:0',
            'periodicite' => 'required|in:mensuel,trimestriel,semestriel,annuel',
            'date_prochaine_echeance' => 'nullable|date',
            'statut' => 'required|in:actif,inactif,termine',
            'document_path' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ];

        // Règles conditionnelles pour le crédit-bail
        if ($request->type == 'leasing') {
            $rules['duree_mois'] = 'required|integer|min:1';
            $rules['valeur_residuelle'] = 'required|numeric|min:0';
            $rules['taux_interet_periodique'] = 'nullable|numeric|min:0|max:100';
        } else {
            $rules['duree_mois'] = 'nullable|integer|min:1';
            $rules['valeur_residuelle'] = 'nullable|numeric|min:0';
            $rules['taux_interet_periodique'] = 'nullable|numeric|min:0|max:100';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('contrats.create')
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $contrat = new Contrat();
            $contrat->dossier_id = $this->getCurrentDossierId();
            $contrat->reference = $request->reference;
            $contrat->type = $request->type;
            $contrat->prestataire_id = $request->prestataire_id;
            $contrat->fournisseur_id = $request->fournisseur_id;
            $contrat->date_debut = $request->date_debut;
            $contrat->date_fin = $request->date_fin;
            $contrat->description = $request->description;
            $contrat->montant_periodique = $request->montant_periodique;
            $contrat->periodicite = $request->periodicite;
            $contrat->date_prochaine_echeance = $request->date_prochaine_echeance;
            $contrat->statut = $request->statut;

            // Gestion du document
            if ($request->hasFile('document_path')) {
                $path = $request->file('document_path')->store('contrats', 'public');
                $contrat->document_path = $path;
            }

            $contrat->save();

            // Si c'est un contrat de crédit-bail, créer les détails et échéances
            if ($request->type == 'leasing') {
                $detailCreditBail = new DetailCreditBail();
                $detailCreditBail->contrat_id = $contrat->id;
                $detailCreditBail->duree_mois = $request->duree_mois;
                $detailCreditBail->valeur_residuelle = $request->valeur_residuelle;
                $detailCreditBail->periodicite = $request->periodicite;
                $detailCreditBail->montant_redevance_periodique = $request->montant_periodique;
                $detailCreditBail->taux_interet_periodique = $request->taux_interet_periodique;

                // Calculer le montant total des redevances
                $montantTotalRedevances = $this->calculerMontantTotalRedevances(
                    $request->montant_periodique,
                    $request->duree_mois,
                    $request->periodicite
                );

                $detailCreditBail->montant_total_redevances = $montantTotalRedevances;
                $detailCreditBail->save();

                // Générer les échéances
                $this->genererEcheancesCreditBail($contrat->id, $request->date_debut);
            }

            DB::commit();

            return redirect()->route('contrats.show', $contrat->id)
                ->with('success', 'Contrat créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('contrats.create')
                ->with('error', 'Erreur lors de la création du contrat: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dossier_id = $this->getCurrentDossierId();
        $contrat = Contrat::where('dossier_id', $dossier_id)
                         ->findOrFail($id);

        $immobilisations = $contrat->immobilisations;

        // Si c'est un contrat de crédit-bail, récupérer les détails et échéances
        $detailCreditBail = null;
        $echeances = null;

        if ($contrat->type == 'leasing') {
            $detailCreditBail = DetailCreditBail::where('contrat_id', $contrat->id)->first();

            if ($detailCreditBail) {
                $echeances = EcheanceCreditBail::where('detail_credit_bail_contrat_id', $contrat->id)
                                              ->orderBy('numero_echeance')
                                              ->get();
            }
        }

        return view('contrats.show', compact('contrat', 'immobilisations', 'detailCreditBail', 'echeances'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dossier_id = $this->getCurrentDossierId();
        $contrat = Contrat::where('dossier_id', $dossier_id)
                         ->findOrFail($id);

        $fournisseurs = \App\Models\Fournisseur::where('dossier_id', $dossier_id)->orderBy('nom')->get();
        $prestataires = \App\Models\Prestataire::where('dossier_id', $dossier_id)->orderBy('nom')->get();

        // Si c'est un contrat de crédit-bail, récupérer les détails
        $detailCreditBail = null;

        if ($contrat->type == 'leasing') {
            $detailCreditBail = DetailCreditBail::where('contrat_id', $contrat->id)->first();
        }

        return view('contrats.edit', compact('contrat', 'fournisseurs', 'prestataires', 'detailCreditBail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dossier_id = $this->getCurrentDossierId();
        $contrat = Contrat::where('dossier_id', $dossier_id)
                         ->findOrFail($id);

        $rules = [
            'reference' => 'required|string|max:50',
            'type' => 'required|in:maintenance,location,leasing,autre',
            'prestataire_id' => 'nullable|exists:prestataires,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'description' => 'nullable|string',
            'montant_periodique' => 'required|numeric|min:0',
            'periodicite' => 'required|in:mensuel,trimestriel,semestriel,annuel',
            'date_prochaine_echeance' => 'nullable|date',
            'statut' => 'required|in:actif,inactif,termine',
            'document_path' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ];

        // Règles conditionnelles pour le crédit-bail
        if ($request->type == 'leasing') {
            $rules['duree_mois'] = 'required|integer|min:1';
            $rules['valeur_residuelle'] = 'required|numeric|min:0';
            $rules['taux_interet_periodique'] = 'nullable|numeric|min:0|max:100';
        } else {
            $rules['duree_mois'] = 'nullable|integer|min:1';
            $rules['valeur_residuelle'] = 'nullable|numeric|min:0';
            $rules['taux_interet_periodique'] = 'nullable|numeric|min:0|max:100';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('contrats.edit', $contrat->id)
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $contrat->reference = $request->reference;
            $contrat->type = $request->type;
            $contrat->prestataire_id = $request->prestataire_id;
            $contrat->fournisseur_id = $request->fournisseur_id;
            $contrat->date_debut = $request->date_debut;
            $contrat->date_fin = $request->date_fin;
            $contrat->description = $request->description;
            $contrat->montant_periodique = $request->montant_periodique;
            $contrat->periodicite = $request->periodicite;
            $contrat->date_prochaine_echeance = $request->date_prochaine_echeance;
            $contrat->statut = $request->statut;

            // Gestion du document
            if ($request->hasFile('document_path')) {
                // Supprimer l'ancien document si existant
                if ($contrat->document_path) {
                    Storage::disk('public')->delete($contrat->document_path);
                }
                $path = $request->file('document_path')->store('contrats', 'public');
                $contrat->document_path = $path;
            }

            $contrat->save();

            // Si c'est un contrat de crédit-bail, mettre à jour ou créer les détails
            if ($request->type == 'leasing') {
                $detailCreditBail = DetailCreditBail::firstOrNew(['contrat_id' => $contrat->id]);
                $detailCreditBail->duree_mois = $request->duree_mois;
                $detailCreditBail->valeur_residuelle = $request->valeur_residuelle;
                $detailCreditBail->periodicite = $request->periodicite;
                $detailCreditBail->montant_redevance_periodique = $request->montant_periodique;
                $detailCreditBail->taux_interet_periodique = $request->taux_interet_periodique;

                // Calculer le montant total des redevances
                $montantTotalRedevances = $this->calculerMontantTotalRedevances(
                    $request->montant_periodique,
                    $request->duree_mois,
                    $request->periodicite
                );

                $detailCreditBail->montant_total_redevances = $montantTotalRedevances;
                $detailCreditBail->save();

                // Vérifier si les échéances existent déjà
                $echeancesExistantes = EcheanceCreditBail::where('detail_credit_bail_contrat_id', $contrat->id)->count();

                // Si les échéances n'existent pas ou si l'utilisateur a demandé de les régénérer
                if ($echeancesExistantes == 0 || $request->has('regenerer_echeances')) {
                    // Supprimer les échéances existantes si nécessaire
                    if ($echeancesExistantes > 0) {
                        EcheanceCreditBail::where('detail_credit_bail_contrat_id', $contrat->id)->delete();
                    }

                    // Générer les nouvelles échéances
                    $this->genererEcheancesCreditBail($contrat->id, $request->date_debut);
                }
            } else {
                // Si le contrat n'est plus de type leasing, supprimer les détails et échéances
                DetailCreditBail::where('contrat_id', $contrat->id)->delete();
                EcheanceCreditBail::where('detail_credit_bail_contrat_id', $contrat->id)->delete();
            }

            DB::commit();

            return redirect()->route('contrats.show', $contrat->id)
                ->with('success', 'Contrat mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('contrats.edit', $contrat->id)
                ->with('error', 'Erreur lors de la mise à jour du contrat: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dossier_id = $this->getCurrentDossierId();
        $contrat = Contrat::where('dossier_id', $dossier_id)
                         ->findOrFail($id);

        DB::beginTransaction();

        try {
            // Supprimer les échéances si c'est un contrat de crédit-bail
            if ($contrat->type == 'leasing') {
                EcheanceCreditBail::where('detail_credit_bail_contrat_id', $contrat->id)->delete();
                DetailCreditBail::where('contrat_id', $contrat->id)->delete();
            }

            // Supprimer le document si existant
            if ($contrat->document_path) {
                Storage::disk('public')->delete($contrat->document_path);
            }

            // Détacher les immobilisations liées
            $contrat->immobilisations()->detach();

            // Supprimer le contrat
            $contrat->delete();

            DB::commit();

            return redirect()->route('contrats.index')
                ->with('success', 'Contrat supprimé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('contrats.show', $contrat->id)
                ->with('error', 'Erreur lors de la suppression du contrat: ' . $e->getMessage());
        }
    }

    /**
     * Affiche la page de gestion des immobilisations liées au contrat.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function immobilisations($id)
    {
        $dossier_id = $this->getCurrentDossierId();
        $contrat = Contrat::where('dossier_id', $dossier_id)
                         ->findOrFail($id);

        // Récupérer les immobilisations déjà liées au contrat
        $immobilisationsLiees = $contrat->immobilisations;

        // Récupérer les sites et services du dossier courant
        $sites = \App\Models\Site::where('dossier_id', $dossier_id)->orderBy('libelle')->get();
        $services = \App\Models\Service::where('dossier_id', $dossier_id)->orderBy('libelle')->get();

        // Filtrer les immobilisations disponibles
        $immobilisationsQuery = Immobilisation::where('dossier_id', $dossier_id)
                                            ->where('statut', 'actif')
                                            ->orderBy('designation');

        // Appliquer les filtres si présents
        if (request('site_id')) {
            $siteId = request('site_id');
            $immobilisationsQuery->whereHas('service', function($query) use ($siteId) {
                $query->where('site_id', $siteId);
            });
        }

        if (request('service_id')) {
            $immobilisationsQuery->where('service_id', request('service_id'));
        }

        if (request('search')) {
            $search = request('search');
            $immobilisationsQuery->where(function($query) use ($search) {
                $query->where('code', 'like', "%{$search}%")
                      ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        // Exclure les immobilisations déjà liées et non déliées
        $immobilisationsLieesIds = $immobilisationsLiees->filter(function($item) {
            return $item->pivot->date_deliaison === null;
        })->pluck('id')->toArray();

        if (!empty($immobilisationsLieesIds)) {
            $immobilisationsQuery->whereNotIn('id', $immobilisationsLieesIds);
        }

        $immobilisationsDisponibles = $immobilisationsQuery->paginate(10);

        // Assurer la compatibilité avec les deux vues
        $immobilisations = $immobilisationsLiees;

        return view('contrats.immobilisations', compact('contrat', 'immobilisationsLiees', 'immobilisationsDisponibles', 'sites', 'services', 'immobilisations'));
    }

    /**
     * Lie des immobilisations au contrat.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lierImmobilisations(Request $request, $id)
    {
        $dossier_id = $this->getCurrentDossierId();
        $contrat = Contrat::where('dossier_id', $dossier_id)
                         ->findOrFail($id);

        // Vérifier si des immobilisations ont été sélectionnées
        if ($request->has('immobilisation_id')) {
            // Cas de la vue ancienne avec un seul ID
            $immobilisations = [$request->immobilisation_id];
        } elseif ($request->has('immobilisations') && is_array($request->immobilisations)) {
            // Cas de la vue nouvelle avec plusieurs IDs
            $immobilisations = $request->immobilisations;
        } else {
            return redirect()->route('contrats.immobilisations', $contrat->id)
                ->with('error', 'Aucune immobilisation sélectionnée.');
        }

        $date_liaison = Carbon::now()->format('Y-m-d');

        // Préparer les données pour l'attachement
        $attachData = [];
        foreach ($immobilisations as $immobilisationId) {
            $attachData[$immobilisationId] = [
                'date_liaison' => $date_liaison,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        // Attacher les immobilisations au contrat
        $contrat->immobilisations()->attach($attachData);

        return redirect()->route('contrats.immobilisations', $contrat->id)
            ->with('success', count($immobilisations) . ' immobilisation(s) liée(s) avec succès.');
    }

    /**
     * Délie une immobilisation du contrat.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $contratId
     * @param  int  $immobilisationId
     * @return \Illuminate\Http\Response
     */
    public function delierImmobilisation(Request $request, $contratId, $immobilisationId)
    {
        $dossier_id = $this->getCurrentDossierId();
        $contrat = Contrat::where('dossier_id', $dossier_id)
                         ->findOrFail($contratId);

        // Vérifier si l'immobilisation est bien liée au contrat
        $pivot = DB::table('contrat_immobilisation')
                  ->where('contrat_id', $contratId)
                  ->where('immobilisation_id', $immobilisationId)
                  ->whereNull('date_deliaison')
                  ->first();

        if (!$pivot) {
            return redirect()->route('contrats.immobilisations', $contrat->id)
                ->with('error', 'Cette immobilisation n\'est pas liée à ce contrat ou a déjà été déliée.');
        }

        // Mettre à jour la date de déliaison
        DB::table('contrat_immobilisation')
          ->where('contrat_id', $contratId)
          ->where('immobilisation_id', $immobilisationId)
          ->update([
              'date_deliaison' => Carbon::now()->format('Y-m-d'),
              'updated_at' => Carbon::now()
          ]);

        return redirect()->route('contrats.immobilisations', $contrat->id)
            ->with('success', 'Immobilisation déliée avec succès.');
    }

    /**
     * Détache une immobilisation du contrat (pour compatibilité avec l'ancienne vue).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $contratId
     * @param  int  $immobilisationId
     * @return \Illuminate\Http\Response
     */
    public function detachImmobilisation(Request $request, $contratId, $immobilisationId)
    {
        return $this->delierImmobilisation($request, $contratId, $immobilisationId);
    }

    /**
     * Affiche la page de gestion des échéances du crédit-bail.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function echeances($id)
    {
        $dossier_id = $this->getCurrentDossierId();
        $contrat = Contrat::where('dossier_id', $dossier_id)
                         ->findOrFail($id);

        // Vérifier si c'est un contrat de crédit-bail
        if ($contrat->type != 'leasing') {
            return redirect()->route('contrats.show', $contrat->id)
                ->with('error', 'Ce contrat n\'est pas un crédit-bail.');
        }

        // Récupérer les détails du crédit-bail
        $detailCreditBail = DetailCreditBail::where('contrat_id', $contrat->id)->firstOrFail();

        // Récupérer les échéances
        $echeances = EcheanceCreditBail::where('detail_credit_bail_contrat_id', $contrat->id)
                                      ->orderBy('numero_echeance')
                                      ->paginate(15);

        return view('contrats.echeances', compact('contrat', 'detailCreditBail', 'echeances'));
    }

    /**
     * Régénère les échéances du crédit-bail.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function regenererEcheances(Request $request, $id)
    {
        $dossier_id = $this->getCurrentDossierId();
        $contrat = Contrat::where('dossier_id', $dossier_id)
                         ->findOrFail($id);

        // Vérifier si c'est un contrat de crédit-bail
        if ($contrat->type != 'leasing') {
            return redirect()->route('contrats.show', $contrat->id)
                ->with('error', 'Ce contrat n\'est pas un crédit-bail.');
        }

        DB::beginTransaction();

        try {
            // Supprimer les échéances existantes
            EcheanceCreditBail::where('detail_credit_bail_contrat_id', $contrat->id)->delete();

            // Générer les nouvelles échéances
            $this->genererEcheancesCreditBail($contrat->id, $contrat->date_debut);

            DB::commit();

            return redirect()->route('contrats.echeances', $contrat->id)
                ->with('success', 'Échéances régénérées avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('contrats.echeances', $contrat->id)
                ->with('error', 'Erreur lors de la régénération des échéances: ' . $e->getMessage());
        }
    }

    /**
     * Marque une échéance comme payée.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $contratId
     * @param  int  $echeanceId
     * @return \Illuminate\Http\Response
     */
    public function payerEcheance(Request $request, $contratId, $echeanceId)
    {
        $dossier_id = $this->getCurrentDossierId();
        $contrat = Contrat::where('dossier_id', $dossier_id)
                         ->findOrFail($contratId);

        // Vérifier si c'est un contrat de crédit-bail
        if ($contrat->type != 'leasing') {
            return redirect()->route('contrats.show', $contrat->id)
                ->with('error', 'Ce contrat n\'est pas un crédit-bail.');
        }

        // Récupérer l'échéance
        $echeance = EcheanceCreditBail::where('detail_credit_bail_contrat_id', $contratId)
                                     ->findOrFail($echeanceId);

        // Vérifier si l'échéance n'est pas déjà payée
        if ($echeance->statut == 'payee') {
            return redirect()->route('contrats.echeances', $contrat->id)
                ->with('error', 'Cette échéance est déjà payée.');
        }

        // Valider la date de paiement
        $validator = Validator::make($request->all(), [
            'date_paiement' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->route('contrats.echeances', $contrat->id)
                ->withErrors($validator)
                ->withInput();
        }

        // Mettre à jour l'échéance
        $echeance->statut = 'payee';
        $echeance->date_paiement = $request->date_paiement;
        $echeance->save();

        return redirect()->route('contrats.echeances', $contrat->id)
            ->with('success', 'Échéance marquée comme payée.');
    }

    /**
     * Annule le paiement d'une échéance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $contratId
     * @param  int  $echeanceId
     * @return \Illuminate\Http\Response
     */
    public function annulerPaiementEcheance(Request $request, $contratId, $echeanceId)
    {
        $dossier_id = $this->getCurrentDossierId();
        $contrat = Contrat::where('dossier_id', $dossier_id)
                         ->findOrFail($contratId);

        // Vérifier si c'est un contrat de crédit-bail
        if ($contrat->type != 'leasing') {
            return redirect()->route('contrats.show', $contrat->id)
                ->with('error', 'Ce contrat n\'est pas un crédit-bail.');
        }

        // Récupérer l'échéance
        $echeance = EcheanceCreditBail::where('detail_credit_bail_contrat_id', $contratId)
                                     ->findOrFail($echeanceId);

        // Vérifier si l'échéance est bien payée
        if ($echeance->statut != 'payee') {
            return redirect()->route('contrats.echeances', $contrat->id)
                ->with('error', 'Cette échéance n\'est pas marquée comme payée.');
        }

        // Déterminer le nouveau statut en fonction de la date d'échéance
        $statut = Carbon::now()->gt(Carbon::parse($echeance->date_echeance)) ? 'en_retard' : 'a_payer';

        // Mettre à jour l'échéance
        $echeance->statut = $statut;
        $echeance->date_paiement = null;
        $echeance->save();

        return redirect()->route('contrats.echeances', $contrat->id)
            ->with('success', 'Paiement de l\'échéance annulé.');
    }

    /**
     * Exporte les échéances du crédit-bail au format CSV.
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportEcheancesCsv($id)
    {
        $dossier_id = $this->getCurrentDossierId();
        $contrat = Contrat::where('dossier_id', $dossier_id)
                         ->findOrFail($id);

        // Vérifier si c'est un contrat de crédit-bail
        if ($contrat->type != 'leasing') {
            return redirect()->route('contrats.show', $contrat->id)
                ->with('error', 'Ce contrat n\'est pas un crédit-bail.');
        }

        // Récupérer les échéances
        $echeances = EcheanceCreditBail::where('detail_credit_bail_contrat_id', $contrat->id)
                                      ->orderBy('numero_echeance')
                                      ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="echeances_' . $contrat->reference . '_' . date('Y-m-d_His') . '.csv"',
        ];

        $callback = function() use ($contrat, $echeances) {
            $file = fopen('php://output', 'w');
            // BOM UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // En-têtes
            fputcsv($file, [
                'Contrat',
                'N° Échéance',
                'Date échéance',
                'Montant redevance',
                'Part intérêt',
                'Part capital',
                'Capital restant dû',
                'Statut',
                'Date paiement'
            ], ';');

            // Données
            foreach ($echeances as $echeance) {
                fputcsv($file, [
                    $contrat->reference,
                    $echeance->numero_echeance,
                    $echeance->date_echeance->format('d/m/Y'),
                    number_format($echeance->montant_redevance, 2, ',', ''),
                    number_format($echeance->part_interet, 2, ',', ''),
                    number_format($echeance->part_capital, 2, ',', ''),
                    number_format($echeance->capital_restant_du, 2, ',', ''),
                    $echeance->statut == 'payee' ? 'Payée' : ($echeance->statut == 'en_retard' ? 'En retard' : 'À payer'),
                    $echeance->date_paiement ? $echeance->date_paiement->format('d/m/Y') : ''
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exporte les échéances du crédit-bail au format Excel.
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportEcheancesExcel($id)
    {
        // Rediriger vers l'export CSV pour le moment
        // À implémenter avec une bibliothèque Excel comme PhpSpreadsheet
        return $this->exportEcheancesCsv($id);
    }

    /**
     * Calcule le montant total des redevances.
     *
     * @param  float  $montantPeriodique
     * @param  int  $dureeMois
     * @param  string  $periodicite
     * @return float
     */
    private function calculerMontantTotalRedevances($montantPeriodique, $dureeMois, $periodicite)
    {
        $nombreEcheances = 0;

        switch ($periodicite) {
            case 'mensuel':
                $nombreEcheances = $dureeMois;
                break;
            case 'trimestriel':
                $nombreEcheances = ceil($dureeMois / 3);
                break;
            case 'semestriel':
                $nombreEcheances = ceil($dureeMois / 6);
                break;
            case 'annuel':
                $nombreEcheances = ceil($dureeMois / 12);
                break;
        }

        return $montantPeriodique * $nombreEcheances;
    }

    /**
     * Génère les échéances du crédit-bail.
     *
     * @param  int  $contratId
     * @param  string  $dateDebut
     * @return void
     */
    private function genererEcheancesCreditBail($contratId, $dateDebut)
    {
        // Récupérer le contrat et les détails du crédit-bail
        $contrat = Contrat::findOrFail($contratId);
        $detailCreditBail = DetailCreditBail::where('contrat_id', $contratId)->firstOrFail();

        // Paramètres
        $dateDebut = Carbon::parse($dateDebut);
        $montantRedevance = $detailCreditBail->montant_redevance_periodique;
        $dureeMois = $detailCreditBail->duree_mois;
        $periodicite = $detailCreditBail->periodicite;
        $tauxInteret = $detailCreditBail->taux_interet_periodique;
        $valeurResiduelle = $detailCreditBail->valeur_residuelle;

        // Calculer le nombre d'échéances et l'intervalle entre chaque échéance
        $nombreEcheances = 0;
        $intervalleMois = 0;

        switch ($periodicite) {
            case 'mensuel':
                $nombreEcheances = $dureeMois;
                $intervalleMois = 1;
                break;
            case 'trimestriel':
                $nombreEcheances = ceil($dureeMois / 3);
                $intervalleMois = 3;
                break;
            case 'semestriel':
                $nombreEcheances = ceil($dureeMois / 6);
                $intervalleMois = 6;
                break;
            case 'annuel':
                $nombreEcheances = ceil($dureeMois / 12);
                $intervalleMois = 12;
                break;
        }

        // Calculer le capital initial (valeur du bien - valeur résiduelle)
        $capitalInitial = $montantRedevance * $nombreEcheances - $valeurResiduelle;
        $capitalRestantDu = $capitalInitial;

        // Générer les échéances
        $echeances = [];
        $dateEcheance = $dateDebut->copy();

        for ($i = 1; $i <= $nombreEcheances; $i++) {
            // Avancer à la prochaine date d'échéance
            if ($i > 1) {
                $dateEcheance->addMonths($intervalleMois);
            }

            // Calculer la part d'intérêt et la part de capital
            $partInteret = 0;
            if ($tauxInteret) {
                // Convertir le taux périodique en taux par échéance
                $tauxParEcheance = $tauxInteret / 100;
                $partInteret = $capitalRestantDu * $tauxParEcheance;
            }

            $partCapital = $montantRedevance - $partInteret;

            // Mettre à jour le capital restant dû
            $capitalRestantDu -= $partCapital;

            // Corriger les erreurs d'arrondi pour la dernière échéance
            if ($i == $nombreEcheances) {
                if (abs($capitalRestantDu - $valeurResiduelle) < 0.01) {
                    $capitalRestantDu = $valeurResiduelle;
                }
            }

            // Déterminer le statut en fonction de la date d'échéance
            $statut = Carbon::now()->gt($dateEcheance) ? 'en_retard' : 'a_payer';

            // Créer l'échéance
            $echeance = new EcheanceCreditBail();
            $echeance->detail_credit_bail_contrat_id = $contratId;
            $echeance->numero_echeance = $i;
            $echeance->date_echeance = $dateEcheance->format('Y-m-d');
            $echeance->montant_redevance = $montantRedevance;
            $echeance->part_interet = $partInteret;
            $echeance->part_capital = $partCapital;
            $echeance->capital_restant_du = $capitalRestantDu;
            $echeance->statut = $statut;
            $echeance->save();

            $echeances[] = $echeance;
        }

        return $echeances;
    }

    /**
     * Récupère les informations d'une famille.
     *
     * @param  int  $familleId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFamilleInfo($familleId)
    {
        $dossier_id = $this->getCurrentDossierId();
        $famille = \App\Models\Famille::where('dossier_id', $dossier_id)
                                     ->findOrFail($familleId);

        return response()->json([
            'comptecompta_immobilisation_id' => $famille->comptecompta_immobilisation_id,
            'comptecompta_amortissement_id' => $famille->comptecompta_amortissement_id,
            'comptecompta_dotation_id' => $famille->comptecompta_dotation_id,
            'duree_amortissement_par_defaut' => $famille->duree_amortissement_par_defaut,
            'methode_amortissement_par_defaut' => $famille->methode_amortissement_par_defaut,
        ]);
    }

    /**
     * Récupère les services d'un site.
     *
     * @param  int  $siteId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getServicesBySite($siteId)
    {
        $dossier_id = $this->getCurrentDossierId();
        $services = \App\Models\Service::where('dossier_id', $dossier_id)
                                      ->where('site_id', $siteId)
                                      ->orderBy('libelle')
                                      ->get(['id', 'libelle']);

        return response()->json($services);
    }
}
