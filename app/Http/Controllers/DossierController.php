<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\Client;
use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DossierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Super admin voit tous les dossiers
        if (Auth::user()->isSuperAdmin()) {
            $dossiers = Dossier::with(['client', 'societe'])->get();
        } 
        // Admin voit les dossiers de son client
        elseif (Auth::user()->isAdmin() && Auth::user()->client_id) {
            $dossiers = Dossier::where('client_id', Auth::user()->client_id)->with(['client', 'societe'])->get();
        } 
        // Autres utilisateurs voient les dossiers auxquels ils ont accès
        else {
            $dossiers = Auth::user()->dossiers()->with(['client', 'societe'])->get();
        }

        return view('dossiers.index', compact('dossiers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Seuls les super admin et admin peuvent créer des dossiers
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            return redirect()->route('dossiers.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour créer un dossier.');
        }

        // Super admin peut choisir n'importe quelle société et client
        if (Auth::user()->isSuperAdmin()) {
            $societes = Societe::all();
            $clients = Client::all();
        } 
        // Admin ne peut choisir que sa société et son client
        else {
            $societes = Societe::where('id', Auth::user()->societe_id)->get();
            $clients = Client::where('societe_id', Auth::user()->societe_id)->get();
        }

        return view('dossiers.create', compact('societes', 'clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'societe_id' => 'required|exists:societes,id',
            'client_id' => 'required|exists:clients,id',
            'code' => 'required|string|max:255|unique:dossiers',
            'nom' => 'required|string|max:255',
            'libelle' => 'required|string|max:255',
            'exercice_debut' => 'nullable|date',
            'exercice_fin' => 'nullable|date|after_or_equal:exercice_debut',
            'est_cloture' => 'boolean',
            'date_cloture' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Vérification des permissions
        if (!Auth::user()->isSuperAdmin()) {
            // Admin ne peut créer que pour sa société et son client
            if (Auth::user()->societe_id != $request->societe_id || Auth::user()->client_id != $request->client_id) {
                return redirect()->route('dossiers.index')
                    ->with('error', 'Vous ne pouvez créer des dossiers que pour votre société et votre client.');
            }
        }

        // Création du dossier
        $dossier = Dossier::create($request->all());

        // Associer l'utilisateur au dossier
        $dossier->users()->attach(Auth::id());

        return redirect()->route('dossiers.show', $dossier->id)
            ->with('success', 'Dossier créé avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dossier  $dossier
     * @return \Illuminate\Http\Response
     */
    public function show(Dossier $dossier)
    {
        // Vérification des permissions
        if (!Auth::user()->canAccessDossier($dossier)) {
            return redirect()->route('dossiers.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour voir ce dossier.');
        }

        $dossier->load('societe', 'client', 'users', 'immobilisations', 'contrats');
        
        return view('dossiers.show', compact('dossier'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dossier  $dossier
     * @return \Illuminate\Http\Response
     */
    public function edit(Dossier $dossier)
    {
        // Vérification des permissions
        if (!Auth::user()->isSuperAdmin() && 
            !(Auth::user()->isAdmin() && Auth::user()->client_id == $dossier->client_id)) {
            return redirect()->route('dossiers.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour modifier ce dossier.');
        }

        // Super admin peut choisir n'importe quelle société et client
        if (Auth::user()->isSuperAdmin()) {
            $societes = Societe::all();
            $clients = Client::all();
        } 
        // Admin ne peut choisir que sa société et son client
        else {
            $societes = Societe::where('id', Auth::user()->societe_id)->get();
            $clients = Client::where('societe_id', Auth::user()->societe_id)->get();
        }

        return view('dossiers.edit', compact('dossier', 'societes', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dossier  $dossier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dossier $dossier)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'societe_id' => 'required|exists:societes,id',
            'client_id' => 'required|exists:clients,id',
            'code' => 'required|string|max:255|unique:dossiers,code,' . $dossier->id,
            'nom' => 'required|string|max:255',
            'libelle' => 'required|string|max:255',
            'exercice_debut' => 'nullable|date',
            'exercice_fin' => 'nullable|date|after_or_equal:exercice_debut',
            'est_cloture' => 'boolean',
            'date_cloture' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Vérification des permissions
        if (!Auth::user()->isSuperAdmin()) {
            // Admin ne peut modifier que pour sa société et son client
            if (Auth::user()->societe_id != $request->societe_id || Auth::user()->client_id != $request->client_id) {
                return redirect()->route('dossiers.index')
                    ->with('error', 'Vous ne pouvez modifier que les dossiers de votre société et votre client.');
            }
        }

        // Mise à jour du dossier
        $dossier->update($request->all());

        return redirect()->route('dossiers.show', $dossier->id)
            ->with('success', 'Dossier mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dossier  $dossier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dossier $dossier)
    {
        // Seul le super admin peut supprimer un dossier
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('dossiers.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour supprimer un dossier.');
        }

        // Vérification si le dossier a des immobilisations ou des contrats
        if ($dossier->immobilisations()->count() > 0 || $dossier->contrats()->count() > 0) {
            return redirect()->route('dossiers.index')
                ->with('error', 'Impossible de supprimer ce dossier car il possède des immobilisations ou des contrats.');
        }

        // Détacher tous les utilisateurs
        $dossier->users()->detach();

        $dossier->delete();

        return redirect()->route('dossiers.index')
            ->with('success', 'Dossier supprimé avec succès.');
    }

    /**
     * Show the dossier selection page.
     *
     * @return \Illuminate\Http\Response
     */
    public function select()
    {
        // Super admin voit tous les dossiers
        if (Auth::user()->isSuperAdmin()) {
            $dossiers = Dossier::with(['client', 'societe'])->get();
        } 
        // Admin voit les dossiers de son client
        elseif (Auth::user()->isAdmin() && Auth::user()->client_id) {
            $dossiers = Dossier::where('client_id', Auth::user()->client_id)->with(['client', 'societe'])->get();
        } 
        // Autres utilisateurs voient les dossiers auxquels ils ont accès
        else {
            $dossiers = Auth::user()->dossiers()->with(['client', 'societe'])->get();
        }

        return view('dossiers.select', compact('dossiers'));
    }

    /**
     * Store the selected dossier in session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSelection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dossier_id' => 'required|exists:dossiers,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Veuillez sélectionner un dossier valide.');
        }

        $dossier = Dossier::findOrFail($request->dossier_id);

        // Vérification des permissions
        if (!Auth::user()->canAccessDossier($dossier)) {
            return redirect()->route('dossiers.select')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour sélectionner ce dossier.');
        }

        // Mise à jour du dossier courant de l'utilisateur
        $user = Auth::user();
        $user->current_dossier_id = $dossier->id;
        $user->save();
        
        // Stocker l'ID du dossier en session pour référence facile
        Session::put('current_dossier_id', $dossier->id);
        
        // Rafraîchir l'utilisateur dans Auth pour s'assurer que les modifications sont visibles immédiatement
        Auth::setUser($user->fresh());

        // Rediriger vers la page d'accueil avec un message de succès
        return redirect()->route('dossiers.index')
            ->with('success', 'Dossier "' . $dossier->nom . '" sélectionné avec succès.');
    }
}
