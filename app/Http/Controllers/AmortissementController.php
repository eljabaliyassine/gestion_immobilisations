<?php

namespace App\Http\Controllers;

use App\Models\Immobilisation;
use App\Models\Amortissement;
use App\Models\PlanAmortissement;
use App\Exports\PlanAmortissementExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class AmortissementController extends Controller
{
    /**
     * Récupère l'ID du dossier courant depuis la session
     * Méthode ajoutée pour cohérence avec les autres contrôleurs
     */
    private function getCurrentDossierId()
    {
        return session("current_dossier_id") ?? session("dossier_id");
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dossierId = $this->getCurrentDossierId();
        Log::info("AmortissementController@index - Dossier ID: " . $dossierId);
        
        $amortissements = Amortissement::with(['immobilisation'])
            ->whereHas('immobilisation', function ($query) use ($dossierId) {
                $query->where('dossier_id', $dossierId);
            })
            ->orderBy('date_amortissement', 'desc')
            ->paginate(15);
            
        return view('amortissements.index', compact('amortissements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dossierId = $this->getCurrentDossierId();
        
        $immobilisations = Immobilisation::where('dossier_id', $dossierId)
            ->orderBy('designation')
            ->get();
            
        return view('amortissements.create', compact('immobilisations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'immobilisation_id' => 'required|exists:immobilisations,id',
            'date_amortissement' => 'required|date',
            'montant' => 'required|numeric|min:0',
            'type_amortissement' => 'required|in:normal,exceptionnel',
            'commentaire' => 'nullable|string|max:500',
        ]);
        
        $dossierId = $this->getCurrentDossierId();
        
        // Vérifier que l'immobilisation appartient au dossier courant
        $immobilisation = Immobilisation::where('id', $request->immobilisation_id)
            ->where('dossier_id', $dossierId)
            ->firstOrFail();
        
        $amortissement = new Amortissement();
        $amortissement->immobilisation_id = $request->immobilisation_id;
        $amortissement->date_amortissement = $request->date_amortissement;
        $amortissement->montant = $request->montant;
        $amortissement->type_amortissement = $request->type_amortissement;
        $amortissement->commentaire = $request->commentaire;
        $amortissement->user_id = Auth::id();
        $amortissement->save();
        
        return redirect()->route('amortissements.show', $amortissement->id)
            ->with('success', 'Amortissement créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Amortissement $amortissement)
    {
        $dossierId = $this->getCurrentDossierId();
        
        // Vérifier que l'amortissement appartient au dossier courant
        if ($amortissement->immobilisation->dossier_id != $dossierId) {
            abort(403, 'Accès non autorisé à cet amortissement.');
        }
        
        return view('amortissements.show', compact('amortissement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Amortissement $amortissement)
    {
        $dossierId = $this->getCurrentDossierId();
        
        // Vérifier que l'amortissement appartient au dossier courant
        if ($amortissement->immobilisation->dossier_id != $dossierId) {
            abort(403, 'Accès non autorisé à cet amortissement.');
        }
        
        $immobilisations = Immobilisation::where('dossier_id', $dossierId)
            ->orderBy('designation')
            ->get();
            
        return view('amortissements.edit', compact('amortissement', 'immobilisations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Amortissement $amortissement)
    {
        $dossierId = $this->getCurrentDossierId();
        
        // Vérifier que l'amortissement appartient au dossier courant
        if ($amortissement->immobilisation->dossier_id != $dossierId) {
            abort(403, 'Accès non autorisé à cet amortissement.');
        }
        
        $request->validate([
            'immobilisation_id' => 'required|exists:immobilisations,id',
            'date_amortissement' => 'required|date',
            'montant' => 'required|numeric|min:0',
            'type_amortissement' => 'required|in:normal,exceptionnel',
            'commentaire' => 'nullable|string|max:500',
        ]);
        
        // Vérifier que la nouvelle immobilisation appartient au dossier courant
        $immobilisation = Immobilisation::where('id', $request->immobilisation_id)
            ->where('dossier_id', $dossierId)
            ->firstOrFail();
        
        $amortissement->immobilisation_id = $request->immobilisation_id;
        $amortissement->date_amortissement = $request->date_amortissement;
        $amortissement->montant = $request->montant;
        $amortissement->type_amortissement = $request->type_amortissement;
        $amortissement->commentaire = $request->commentaire;
        $amortissement->save();
        
        return redirect()->route('amortissements.show', $amortissement->id)
            ->with('success', 'Amortissement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Amortissement $amortissement)
    {
        $dossierId = $this->getCurrentDossierId();
        
        // Vérifier que l'amortissement appartient au dossier courant
        if ($amortissement->immobilisation->dossier_id != $dossierId) {
            abort(403, 'Accès non autorisé à cet amortissement.');
        }
        
        $amortissement->delete();
        
        return redirect()->route('amortissements.index')
            ->with('success', 'Amortissement supprimé avec succès.');
    }
    
    /**
     * Show the form for creating a new exceptional depreciation.
     */
    public function createExceptionnel(Immobilisation $immobilisation)
    {
        $dossierId = $this->getCurrentDossierId();
        
        // Vérifier que l'immobilisation appartient au dossier courant
        if ($immobilisation->dossier_id != $dossierId) {
            abort(403, 'Accès non autorisé à cette immobilisation.');
        }
        
        return view('amortissements.create_exceptionnel', compact('immobilisation'));
    }
    
    /**
     * Store a newly created exceptional depreciation.
     */
    public function storeExceptionnel(Request $request, Immobilisation $immobilisation)
    {
        $dossierId = $this->getCurrentDossierId();
        
        // Vérifier que l'immobilisation appartient au dossier courant
        if ($immobilisation->dossier_id != $dossierId) {
            abort(403, 'Accès non autorisé à cette immobilisation.');
        }
        
        $request->validate([
            'date_amortissement' => 'required|date',
            'montant' => 'required|numeric|min:0',
            'commentaire' => 'nullable|string|max:500',
        ]);
        
        $amortissement = new Amortissement();
        $amortissement->immobilisation_id = $immobilisation->id;
        $amortissement->date_amortissement = $request->date_amortissement;
        $amortissement->montant = $request->montant;
        $amortissement->type_amortissement = 'exceptionnel';
        $amortissement->commentaire = $request->commentaire;
        $amortissement->user_id = Auth::id();
        $amortissement->save();
        
        return redirect()->route('immobilisations.show', $immobilisation->id)
            ->with('success', 'Amortissement exceptionnel créé avec succès.');
    }

    /**
     * Affiche la page du plan d'amortissement avec le formulaire de sélection des dates.
     */
    public function planAmortissement()
    {
        $dossierId = $this->getCurrentDossierId();
        Log::info("AmortissementController@planAmortissement - Dossier ID: " . $dossierId);
        
        // Récupérer les dates par défaut si elles existent en session
        $dateDerniereCloture = session('date_derniere_cloture') ?? Carbon::now()->subYear()->endOfYear()->format('Y-m-d');
        $dateProchaineCloture = session('date_prochaine_cloture') ?? Carbon::now()->endOfYear()->format('Y-m-d');
        
        Log::info("Dates par défaut: dernière clôture = {$dateDerniereCloture}, prochaine clôture = {$dateProchaineCloture}");
        
        // Vérifier si un plan d'amortissement existe déjà pour ces dates
        $plansAmortissement = PlanAmortissement::where('dossier_id', $dossierId)
            ->where('date_derniere_cloture', $dateDerniereCloture)
            ->where('date_prochaine_cloture', $dateProchaineCloture)
            ->orderBy('immobilisations_libelle')
            ->orderBy('annee_exercice')
            ->get();
        
        Log::info("Nombre de plans d'amortissement trouvés: " . $plansAmortissement->count());
        
        return view('amortissements.plan', compact('plansAmortissement', 'dateDerniereCloture', 'dateProchaineCloture'));
    }

    /**
     * Génère le plan d'amortissement pour les dates sélectionnées.
     */
    public function genererPlanAmortissement(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'date_derniere_cloture' => 'required|date',
            'date_prochaine_cloture' => 'required|date|after:date_derniere_cloture',
        ]);
        
        // Récupération du dossier courant
        $dossierId = $this->getCurrentDossierId();
        $dateDerniereCloture = $request->date_derniere_cloture;
        $dateProchaineCloture = $request->date_prochaine_cloture;
        
        Log::info("AmortissementController@genererPlanAmortissement - Paramètres:", [
            'dossier_id' => $dossierId,
            'date_derniere_cloture' => $dateDerniereCloture,
            'date_prochaine_cloture' => $dateProchaineCloture
        ]);
        
        // Stocker les dates en session pour les réutiliser
        session(['date_derniere_cloture' => $dateDerniereCloture]);
        session(['date_prochaine_cloture' => $dateProchaineCloture]);
        
        // Appel de la méthode qui va générer le plan d'amortissement
        $this->regenererPlanAmortissement($dossierId, $dateDerniereCloture, $dateProchaineCloture);
        
        // Récupération des données pour l'affichage
        $plansAmortissement = PlanAmortissement::where('dossier_id', $dossierId)
            ->where('date_derniere_cloture', $dateDerniereCloture)
            ->where('date_prochaine_cloture', $dateProchaineCloture)
            ->orderBy('immobilisations_libelle')
            ->orderBy('annee_exercice')
            ->get();
        
        Log::info("Nombre de plans d'amortissement générés: " . $plansAmortissement->count());
        
        // Ajouter des informations de diagnostic dans la vue
        $debug = [
            'dossier_id' => $dossierId,
            'date_derniere_cloture' => $dateDerniereCloture,
            'date_prochaine_cloture' => $dateProchaineCloture,
            'nombre_plans' => $plansAmortissement->count()
        ];
        
        return view('amortissements.plan', [
            'plansAmortissement' => $plansAmortissement,
            'dateDerniereCloture' => $dateDerniereCloture,
            'dateProchaineCloture' => $dateProchaineCloture,
            'success' => 'Plan d\'amortissement généré avec succès.',
            'debug' => $debug
        ]);
    }

    /**
     * Exporte le plan d'amortissement au format Excel.
     */
    public function exportPlanAmortissement(Request $request)
    {
        $dossierId = $this->getCurrentDossierId();
        $dateDerniereCloture = $request->date_derniere_cloture;
        $dateProchaineCloture = $request->date_prochaine_cloture;
        
        // Vérifier si les dates sont fournies, sinon utiliser celles en session
        if (!$dateDerniereCloture || !$dateProchaineCloture) {
            $dateDerniereCloture = session('date_derniere_cloture');
            $dateProchaineCloture = session('date_prochaine_cloture');
        }
        
        // Si toujours pas de dates, utiliser les dates par défaut
        if (!$dateDerniereCloture || !$dateProchaineCloture) {
            $dateDerniereCloture = Carbon::now()->subYear()->endOfYear()->format('Y-m-d');
            $dateProchaineCloture = Carbon::now()->endOfYear()->format('Y-m-d');
        }
        
        Log::info("AmortissementController@exportPlanAmortissement - Paramètres:", [
            'dossier_id' => $dossierId,
            'date_derniere_cloture' => $dateDerniereCloture,
            'date_prochaine_cloture' => $dateProchaineCloture
        ]);
        
        $fileName = 'plan_amortissement_' . Carbon::now()->format('Ymd_His') . '.xlsx';
        
        return Excel::download(new PlanAmortissementExport(
            $dossierId, 
            $dateDerniereCloture, 
            $dateProchaineCloture
        ), $fileName);
    }

    /**
     * Méthode privée pour régénérer le plan d'amortissement.
     */
    private function regenererPlanAmortissement($dossierId, $dateDerniereCloture, $dateProchaineCloture)
    {
        Log::info("Début de regenererPlanAmortissement", [
            'dossier_id' => $dossierId,
            'date_derniere_cloture' => $dateDerniereCloture,
            'date_prochaine_cloture' => $dateProchaineCloture
        ]);
        
        // Supprimer TOUS les enregistrements du dossier courant, quelle que soit la période
        $deleted = DB::table('plans_amortissement')
            ->where('dossier_id', $dossierId)
            ->delete();
            
        Log::info("Enregistrements supprimés pour le dossier {$dossierId}: " . $deleted);
        
        // Récupérer les immobilisations actives du dossier courant
        // Sans filtre sur la date d'acquisition pour permettre toute période
        $immobilisations = DB::table('immobilisations as i')
            ->join('familles as f', 'i.famille_id', '=', 'f.id')
            ->where('i.dossier_id', $dossierId)
            ->where('i.statut', 'actif')
            ->whereNotNull('i.date_mise_service')
            ->select(
                'i.id',
                'i.dossier_id',
                'i.libelle',
                'i.description',
                'i.famille_id',
                'f.libelle as famille_libelle',
                'i.date_acquisition',
                'i.date_mise_service',
                'i.base_amortissement',
                'i.methode_amortissement',
                'i.duree_amortissement',
                'i.coefficient_degressif',
                'f.duree_amortissement_par_defaut'
            )
            ->get();
        
        Log::info("Nombre d'immobilisations actives trouvées: " . $immobilisations->count());
        
        // Afficher les immobilisations trouvées pour diagnostic
        foreach ($immobilisations as $index => $immo) {
            Log::info("Immobilisation #{$index}", [
                'id' => $immo->id,
                'libelle' => $immo->libelle,
                'statut' => 'actif', // On sait que c'est actif car filtré dans la requête
                'date_acquisition' => $immo->date_acquisition,
                'date_mise_service' => $immo->date_mise_service,
                'famille_id' => $immo->famille_id,
                'duree_amortissement_par_defaut' => $immo->duree_amortissement_par_defaut
            ]);
        }
        
        // Préparer les données à insérer
        $donnees = [];
        
        foreach ($immobilisations as $immobilisation) {
            try {
                // Vérifier les valeurs critiques
                if (!$immobilisation->duree_amortissement) {
                    Log::warning("Immobilisation sans durée d'amortissement: ID {$immobilisation->id}");
                    continue;
                }
                
                if (!$immobilisation->duree_amortissement_par_defaut) {
                    Log::warning("Famille sans durée d'amortissement par défaut: ID {$immobilisation->famille_id}");
                    continue;
                }
                
                // Calculer le taux appliqué selon la méthode d'amortissement
                $tauxApplique = $immobilisation->methode_amortissement == 'lineaire' 
                    ? 1 / $immobilisation->duree_amortissement 
                    : (1 / $immobilisation->duree_amortissement) * ($immobilisation->coefficient_degressif ?? 1.25);
                
                // Calculer la durée en jours entre les deux dates de clôture
                $duree_periode = Carbon::parse($dateProchaineCloture)->diffInDays(Carbon::parse($dateDerniereCloture));
                
                // Calculer l'amortissement cumulé début
                if (Carbon::parse($immobilisation->date_mise_service)->gt(Carbon::parse($dateDerniereCloture))) {
    		$amortissement_cumule_debut = 0;
		} else {
	    $jours = Carbon::parse($dateDerniereCloture)->diffInDays(Carbon::parse($immobilisation->date_mise_service));
	    $annees = $jours / 365;
	    $amortissement_cumule_debut = $immobilisation->base_amortissement * $tauxApplique * $annees;
		}

                
                // S'assurer que l'amortissement cumulé début ne dépasse pas la base amortissable
                $amortissement_cumule_debut = min($amortissement_cumule_debut, $immobilisation->base_amortissement);
                
                // Calculer l'amortissement cumulé fin
                if (Carbon::parse($immobilisation->date_mise_service)->gt(Carbon::parse($dateProchaineCloture))) {
		    $amortissement_cumule_fin = 0;
		} else {
	    $jours = Carbon::parse($dateProchaineCloture)->diffInDays(Carbon::parse($immobilisation->date_mise_service));
	    $annees = $jours / 365;
	    $amortissement_cumule_fin = $immobilisation->base_amortissement * $tauxApplique * $annees;
		}

                
                // S'assurer que l'amortissement cumulé fin ne dépasse pas la base amortissable
                $amortissement_cumule_fin = min($amortissement_cumule_fin, $immobilisation->base_amortissement);
                
                // Calculer la dotation pour la période comme la différence entre amortissement cumulé fin et début
                $dotation_periode = $amortissement_cumule_fin - $amortissement_cumule_debut;
                
                // Calculer la VNA fin exercice
                $vna_fin_exercice = $immobilisation->base_amortissement - $amortissement_cumule_fin;
                
                // Calculer la dotation annuelle
                $dotation_annuelle = $immobilisation->base_amortissement * $tauxApplique;
                
                // Ajouter les données à insérer
                $donnees[] = [
                    'dossier_id' => $immobilisation->dossier_id,
                    'immobilisation_id' => $immobilisation->id,
                    'immobilisations_libelle' => $immobilisation->libelle,
                    'immobilisations_description' => $immobilisation->description,
                    'immobilisations_famille_id' => $immobilisation->famille_id,
                    'famille' => $immobilisation->famille_libelle,
                    'immobilisation_date_acquisition' => $immobilisation->date_acquisition,
                    'immobilisation_date_mise_service' => $immobilisation->date_mise_service,
                    'annee_exercice' => Carbon::parse($dateProchaineCloture)->year,
                    'base_amortissable' => $immobilisation->base_amortissement,
                    'taux_applique' => $tauxApplique,
                    'date_derniere_cloture' => $dateDerniereCloture,
                    'date_prochaine_cloture' => $dateProchaineCloture,
                    'duree_periode' => $duree_periode,
                    'dotation_periode' => $dotation_periode,
                    'duree_amortissement_par_defaut' => $immobilisation->duree_amortissement_par_defaut,
                    'amortissement_cumule_debut' => $amortissement_cumule_debut,
                    'amortissement_cumule_fin' => $amortissement_cumule_fin,
                    'vna_fin_exercice' => $vna_fin_exercice,
                    'dotation_annuelle' => $dotation_annuelle,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                Log::info("Plan d'amortissement calculé pour immobilisation ID {$immobilisation->id}", [
                    'taux_applique' => $tauxApplique,
                    'duree_periode' => $duree_periode,
                    'dotation_periode' => $dotation_periode,
                    'amortissement_cumule_debut' => $amortissement_cumule_debut,
                    'amortissement_cumule_fin' => $amortissement_cumule_fin,
                    'vna_fin_exercice' => $vna_fin_exercice
                ]);
                
            } catch (\Exception $e) {
                Log::error("Erreur lors du calcul pour l'immobilisation ID {$immobilisation->id}: " . $e->getMessage());
            }
        }
        
        Log::info("Nombre de plans d'amortissement à insérer: " . count($donnees));
        
        // Insérer les données par lots pour de meilleures performances
        if (!empty($donnees)) {
            try {
                DB::table('plans_amortissement')->insert($donnees);
                Log::info("Insertion réussie des plans d'amortissement");
            } catch (\Exception $e) {
                Log::error("Erreur lors de l'insertion des plans d'amortissement: " . $e->getMessage());
            }
        } else {
            Log::warning("Aucun plan d'amortissement à insérer");
        }
    }
}
