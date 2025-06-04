<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Models\Immobilisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
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
        ]);

        if ($validator->fails()) {
            return redirect()->route('contrats.create')
                ->withErrors($validator)
                ->withInput();
        }

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

        return redirect()->route('contrats.show', $contrat->id)
            ->with('success', 'Contrat créé avec succès.');
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
        
        return view('contrats.show', compact('contrat', 'immobilisations'));
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
        
        return view('contrats.edit', compact('contrat', 'fournisseurs', 'prestataires'));
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
        
        $validator = Validator::make($request->all(), [
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
        ]);

        if ($validator->fails()) {
            return redirect()->route('contrats.edit', $contrat->id)
                ->withErrors($validator)
                ->withInput();
        }

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

        return redirect()->route('contrats.show', $contrat->id)
            ->with('success', 'Contrat mis à jour avec succès.');
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
        
        // Vérifier si des immobilisations sont liées à ce contrat
        if ($contrat->immobilisations()->count() > 0) {
            return redirect()->route('contrats.show', $contrat->id)
                ->with('error', 'Impossible de supprimer ce contrat car des immobilisations y sont liées.');
        }
        
        // Supprimer le document si existant
        if ($contrat->document_path) {
            Storage::disk('public')->delete($contrat->document_path);
        }
        
        $contrat->delete();
        
        return redirect()->route('contrats.index')
            ->with('success', 'Contrat supprimé avec succès.');
    }

    /**
     * Display the immobilisations linked to the contract.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function immobilisations($id)
    {
        $dossier_id = $this->getCurrentDossierId();
        $contrat = Contrat::where('dossier_id', $dossier_id)
                         ->findOrFail($id);
        
        $immobilisations = $contrat->immobilisations;
        
        // Immobilisations disponibles pour ajout au contrat
        $immobilisationsDisponibles = Immobilisation::where('dossier_id', $dossier_id)
                                                  ->whereNotIn('id', $immobilisations->pluck('id'))
                                                  ->orderBy('designation')
                                                  ->get();
        
        return view('contrats.immobilisations', compact('contrat', 'immobilisations', 'immobilisationsDisponibles'));
    }

    /**
     * Attach an immobilisation to the contract.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function attachImmobilisation(Request $request, $id)
    {
        $dossier_id = $this->getCurrentDossierId();
        $contrat = Contrat::where('dossier_id', $dossier_id)
                         ->findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'immobilisation_id' => 'required|exists:immobilisations,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('contrats.immobilisations', $contrat->id)
                ->withErrors($validator)
                ->withInput();
        }

        $immobilisation = Immobilisation::where('dossier_id', $dossier_id)
                                       ->findOrFail($request->immobilisation_id);
        
        // Vérifier si l'immobilisation n'est pas déjà liée au contrat
        if (!$contrat->immobilisations->contains($immobilisation->id)) {
            $contrat->immobilisations()->attach($immobilisation->id, [
                'date_liaison' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            return redirect()->route('contrats.immobilisations', $contrat->id)
                ->with('success', 'Immobilisation ajoutée au contrat avec succès.');
        }
        
        return redirect()->route('contrats.immobilisations', $contrat->id)
            ->with('error', 'Cette immobilisation est déjà liée à ce contrat.');
    }

    /**
     * Detach an immobilisation from the contract.
     *
     * @param  int  $id
     * @param  int  $immobilisationId
     * @return \Illuminate\Http\Response
     */
    public function detachImmobilisation($id, $immobilisationId)
    {
        $dossier_id = $this->getCurrentDossierId();
        $contrat = Contrat::where('dossier_id', $dossier_id)
                         ->findOrFail($id);
        
        $immobilisation = Immobilisation::where('dossier_id', $dossier_id)
                                       ->findOrFail($immobilisationId);
        
        // Mettre à jour la date de déliaison avant de détacher
        $contrat->immobilisations()->updateExistingPivot($immobilisation->id, [
            'date_deliaison' => now()
        ]);
        
        $contrat->immobilisations()->detach($immobilisation->id);
        
        return redirect()->route('contrats.immobilisations', $contrat->id)
            ->with('success', 'Immobilisation retirée du contrat avec succès.');
    }
}
