<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Super admin voit tous les clients
        if (Auth::user()->isSuperAdmin()) {
            $clients = Client::with('societe')->get();
        } 
        // Admin voit les clients de sa société
        elseif (Auth::user()->isAdmin() && Auth::user()->societe_id) {
            $clients = Client::where('societe_id', Auth::user()->societe_id)->get();
        } 
        // Autres utilisateurs voient uniquement leur client
        else {
            $clients = Client::where('id', Auth::user()->client_id)->get();
        }

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Seuls les super admin et admin peuvent créer des clients
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            return redirect()->route('clients.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour créer un client.');
        }

        // Super admin peut choisir n'importe quelle société
        if (Auth::user()->isSuperAdmin()) {
            $societes = Societe::all();
        } 
        // Admin ne peut choisir que sa société
        else {
            $societes = Societe::where('id', Auth::user()->societe_id)->get();
        }

        return view('clients.create', compact('societes'));
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
            'code' => 'required|string|max:255|unique:clients',
            'nom' => 'required|string|max:255',
            'identifiant_unique' => 'required|string|max:255',
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

        // Vérification des permissions
        if (!Auth::user()->isSuperAdmin()) {
            // Admin ne peut créer que pour sa société
            if (Auth::user()->societe_id != $request->societe_id) {
                return redirect()->route('clients.index')
                    ->with('error', 'Vous ne pouvez créer des clients que pour votre société.');
            }
        }

        // Création du client
        $client = Client::create($request->all());

        return redirect()->route('clients.show', $client->id)
            ->with('success', 'Client créé avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        // Vérification des permissions
        if (!Auth::user()->isSuperAdmin() && 
            !Auth::user()->isAdmin() && 
            Auth::user()->client_id != $client->id) {
            return redirect()->route('clients.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour voir ce client.');
        }

        // Admin ne peut voir que les clients de sa société
        if (Auth::user()->isAdmin() && Auth::user()->societe_id != $client->societe_id) {
            return redirect()->route('clients.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour voir ce client.');
        }

        $client->load('societe', 'dossiers', 'users');
        
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        // Vérification des permissions
        if (!Auth::user()->isSuperAdmin() && 
            !Auth::user()->isAdmin()) {
            return redirect()->route('clients.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour modifier ce client.');
        }

        // Admin ne peut modifier que les clients de sa société
        if (Auth::user()->isAdmin() && Auth::user()->societe_id != $client->societe_id) {
            return redirect()->route('clients.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour modifier ce client.');
        }

        // Super admin peut choisir n'importe quelle société
        if (Auth::user()->isSuperAdmin()) {
            $societes = Societe::all();
        } 
        // Admin ne peut choisir que sa société
        else {
            $societes = Societe::where('id', Auth::user()->societe_id)->get();
        }

        return view('clients.edit', compact('client', 'societes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'societe_id' => 'required|exists:societes,id',
            'code' => 'required|string|max:255|unique:clients,code,' . $client->id,
            'nom' => 'required|string|max:255',
            'identifiant_unique' => 'required|string|max:255',
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

        // Vérification des permissions
        if (!Auth::user()->isSuperAdmin()) {
            // Admin ne peut modifier que pour sa société
            if (Auth::user()->societe_id != $request->societe_id) {
                return redirect()->route('clients.index')
                    ->with('error', 'Vous ne pouvez modifier que les clients de votre société.');
            }
        }

        // Mise à jour du client
        $client->update($request->all());

        return redirect()->route('clients.show', $client->id)
            ->with('success', 'Client mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        // Seul le super admin peut supprimer un client
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('clients.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour supprimer un client.');
        }

        // Vérification si le client a des dossiers ou des utilisateurs
        if ($client->dossiers()->count() > 0 || $client->users()->count() > 0) {
            return redirect()->route('clients.index')
                ->with('error', 'Impossible de supprimer ce client car il possède des dossiers ou des utilisateurs.');
        }

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }
}
