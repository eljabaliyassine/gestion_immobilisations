<?php

namespace App\Http\Controllers;

use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SocieteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Seul le super admin peut voir toutes les sociétés
        if (Auth::user()->isSuperAdmin()) {
            $societes = Societe::all();
        } else {
            // Les autres utilisateurs ne voient que leur société
            $societes = Societe::where('id', Auth::user()->societe_id)->get();
        }

        return view('societes.index', compact('societes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Seul le super admin peut créer des sociétés
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('societes.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour créer une société.');
        }

        return view('societes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Seul le super admin peut créer des sociétés
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('societes.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour créer une société.');
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:societes',
            'nom' => 'required|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'pays' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'siret' => 'nullable|string|max:255',
            'est_actif' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Création de la société
        $societe = Societe::create($request->all());

        return redirect()->route('societes.show', $societe->id)
            ->with('success', 'Société créée avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Societe  $societe
     * @return \Illuminate\Http\Response
     */
    public function show(Societe $societe)
    {
        // Vérification des permissions
        if (!Auth::user()->isSuperAdmin() && Auth::user()->societe_id != $societe->id) {
            return redirect()->route('societes.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour voir cette société.');
        }

        $societe->load('clients', 'dossiers', 'users');
        
        return view('societes.show', compact('societe'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Societe  $societe
     * @return \Illuminate\Http\Response
     */
    public function edit(Societe $societe)
    {
        // Seul le super admin peut modifier des sociétés
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('societes.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour modifier cette société.');
        }

        return view('societes.edit', compact('societe'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Societe  $societe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Societe $societe)
    {
        // Seul le super admin peut modifier des sociétés
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('societes.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour modifier cette société.');
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:societes,code,' . $societe->id,
            'nom' => 'required|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'pays' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'siret' => 'nullable|string|max:255',
            'est_actif' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Mise à jour de la société
        $societe->update($request->all());

        return redirect()->route('societes.show', $societe->id)
            ->with('success', 'Société mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Societe  $societe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Societe $societe)
    {
        // Seul le super admin peut supprimer des sociétés
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('societes.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour supprimer cette société.');
        }

        // Vérification si la société a des clients, des dossiers ou des utilisateurs
        if ($societe->clients()->count() > 0 || $societe->dossiers()->count() > 0 || $societe->users()->count() > 0) {
            return redirect()->route('societes.index')
                ->with('error', 'Impossible de supprimer cette société car elle possède des clients, des dossiers ou des utilisateurs.');
        }

        $societe->delete();

        return redirect()->route('societes.index')
            ->with('success', 'Société supprimée avec succès.');
    }
}
