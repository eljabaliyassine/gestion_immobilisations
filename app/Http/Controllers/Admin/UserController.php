<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Societe;
use App\Models\Client;
use App\Models\Dossier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Super admin voit tous les utilisateurs
        if (Auth::user()->isSuperAdmin()) {
            $users = User::with(['role', 'societe', 'client', 'currentDossier'])->get();
        } 
        // Admin voit les utilisateurs de son client
        elseif (Auth::user()->isAdmin() && Auth::user()->client_id) {
            $users = User::where('client_id', Auth::user()->client_id)
                ->with(['role', 'societe', 'client', 'currentDossier'])
                ->get();
        } 
        // Autres utilisateurs ne voient que leur propre profil
        else {
            $users = User::where('id', Auth::id())
                ->with(['role', 'societe', 'client', 'currentDossier'])
                ->get();
        }

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Seuls les super admin et admin peuvent créer des utilisateurs
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour créer un utilisateur.');
        }

        $roles = Role::all();

        // Super admin peut choisir n'importe quelle société et client
        if (Auth::user()->isSuperAdmin()) {
            $societes = Societe::all();
            $clients = Client::all();
            $dossiers = Dossier::all();
        } 
        // Admin ne peut choisir que sa société et son client
        else {
            $societes = Societe::where('id', Auth::user()->societe_id)->get();
            $clients = Client::where('societe_id', Auth::user()->societe_id)->get();
            $dossiers = Dossier::where('client_id', Auth::user()->client_id)->get();
        }

        return view('admin.users.create', compact('roles', 'societes', 'clients', 'dossiers'));
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'societe_id' => 'required|exists:societes,id',
            'client_id' => 'nullable|exists:clients,id',
            'current_dossier_id' => 'nullable|exists:dossiers,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Vérification des permissions
        if (!Auth::user()->isSuperAdmin()) {
            // Admin ne peut créer que pour sa société et son client
            if (Auth::user()->societe_id != $request->societe_id || 
                (Auth::user()->client_id != $request->client_id && $request->client_id)) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'Vous ne pouvez créer des utilisateurs que pour votre société et votre client.');
            }

            // Admin ne peut pas créer de super admin
            $role = Role::find($request->role_id);
            if ($role && $role->name === 'super_admin') {
                return redirect()->route('admin.users.index')
                    ->with('error', 'Vous ne pouvez pas créer d\'utilisateur avec le rôle super admin.');
            }
        }

        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'societe_id' => $request->societe_id,
            'client_id' => $request->client_id,
            'current_dossier_id' => $request->current_dossier_id,
        ]);

        // Si un dossier par défaut est sélectionné, l'ajouter aux dossiers accessibles
        if ($request->current_dossier_id) {
            $user->dossiers()->attach($request->current_dossier_id);
        }

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // Vérification des permissions
        if (!Auth::user()->isSuperAdmin() && 
            !Auth::user()->isAdmin() && 
            Auth::id() != $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour voir cet utilisateur.');
        }

        // Admin ne peut voir que les utilisateurs de son client
        if (Auth::user()->isAdmin() && 
            !Auth::user()->isSuperAdmin() && 
            Auth::user()->client_id != $user->client_id && 
            Auth::id() != $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez voir que les utilisateurs de votre client.');
        }

        $user->load('role', 'societe', 'client', 'currentDossier', 'dossiers');
        
        // Pour la gestion des accès
        if (Auth::user()->isSuperAdmin()) {
            $allDossiers = Dossier::all();
        } elseif (Auth::user()->isAdmin()) {
            $allDossiers = Dossier::where('client_id', Auth::user()->client_id)->get();
        } else {
            $allDossiers = collect();
        }
        
        return view('admin.users.show', compact('user', 'allDossiers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // Vérification des permissions
        if (!Auth::user()->isSuperAdmin() && 
            !Auth::user()->isAdmin() && 
            Auth::id() != $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour modifier cet utilisateur.');
        }

        // Admin ne peut modifier que les utilisateurs de son client
        if (Auth::user()->isAdmin() && 
            !Auth::user()->isSuperAdmin() && 
            Auth::user()->client_id != $user->client_id && 
            Auth::id() != $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez modifier que les utilisateurs de votre client.');
        }

        $roles = Role::all();

        // Super admin peut choisir n'importe quelle société et client
        if (Auth::user()->isSuperAdmin()) {
            $societes = Societe::all();
            $clients = Client::all();
            $dossiers = Dossier::all();
        } 
        // Admin ne peut choisir que sa société et son client
        elseif (Auth::user()->isAdmin()) {
            $societes = Societe::where('id', Auth::user()->societe_id)->get();
            $clients = Client::where('societe_id', Auth::user()->societe_id)->get();
            $dossiers = Dossier::where('client_id', Auth::user()->client_id)->get();
        }
        // Utilisateur normal ne peut modifier que son profil
        else {
            $societes = Societe::where('id', Auth::user()->societe_id)->get();
            $clients = Client::where('id', Auth::user()->client_id)->get();
            $dossiers = Auth::user()->dossiers;
        }

        return view('admin.users.edit', compact('user', 'roles', 'societes', 'clients', 'dossiers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // Validation
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'societe_id' => 'required|exists:societes,id',
            'client_id' => 'nullable|exists:clients,id',
            'current_dossier_id' => 'nullable|exists:dossiers,id',
        ];

        // Si le mot de passe est fourni, le valider
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Vérification des permissions
        if (!Auth::user()->isSuperAdmin()) {
            // Admin ne peut modifier que pour sa société et son client
            if (Auth::user()->isAdmin()) {
                if (Auth::user()->societe_id != $request->societe_id || 
                    (Auth::user()->client_id != $request->client_id && $request->client_id)) {
                    return redirect()->route('admin.users.index')
                        ->with('error', 'Vous ne pouvez modifier que les utilisateurs de votre société et votre client.');
                }
            }
            // Utilisateur normal ne peut modifier que son profil
            elseif (Auth::id() == $user->id) {
                // Ne peut pas changer son rôle, sa société ou son client
                if ($user->role_id != $request->role_id || 
                    $user->societe_id != $request->societe_id || 
                    $user->client_id != $request->client_id) {
                    return redirect()->route('admin.users.index')
                        ->with('error', 'Vous ne pouvez pas modifier votre rôle, votre société ou votre client.');
                }
            }
            else {
                return redirect()->route('admin.users.index')
                    ->with('error', 'Vous n\'avez pas les permissions nécessaires pour modifier cet utilisateur.');
            }

            // Admin ne peut pas modifier un utilisateur en super admin
            $role = Role::find($request->role_id);
            if ($role && $role->name === 'super_admin') {
                return redirect()->route('admin.users.index')
                    ->with('error', 'Vous ne pouvez pas attribuer le rôle super admin.');
            }
        }

        // Mise à jour des données
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'societe_id' => $request->societe_id,
            'client_id' => $request->client_id,
            'current_dossier_id' => $request->current_dossier_id,
        ];

        // Mise à jour du mot de passe si fourni
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Si un dossier par défaut est sélectionné, s'assurer qu'il est dans les dossiers accessibles
        if ($request->current_dossier_id && !$user->dossiers->contains($request->current_dossier_id)) {
            $user->dossiers()->attach($request->current_dossier_id);
        }

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Vérification des permissions
        if (!Auth::user()->isSuperAdmin() && 
            !(Auth::user()->isAdmin() && Auth::user()->client_id == $user->client_id)) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour supprimer cet utilisateur.');
        }

        // Ne pas supprimer son propre compte
        if (Auth::id() == $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Ne pas supprimer un super admin si on n'est pas super admin
        if (!Auth::user()->isSuperAdmin() && $user->isSuperAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer un super administrateur.');
        }

        // Détacher tous les dossiers
        $user->dossiers()->detach();

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Update user's accessible dossiers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateDossiers(Request $request, User $user)
    {
        // Vérification des permissions
        if (!Auth::user()->isSuperAdmin() && 
            !(Auth::user()->isAdmin() && Auth::user()->client_id == $user->client_id)) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour modifier les accès de cet utilisateur.');
        }

        // Synchroniser les dossiers
        $dossiers = $request->dossiers ?? [];
        $user->dossiers()->sync($dossiers);

        // Si le dossier courant n'est plus accessible, le réinitialiser
        if ($user->current_dossier_id && !in_array($user->current_dossier_id, $dossiers)) {
            $user->update(['current_dossier_id' => null]);
        }

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Accès aux dossiers mis à jour avec succès.');
    }
}
