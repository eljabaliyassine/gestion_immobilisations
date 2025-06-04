<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth; // To potentially restrict access

class CompteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Authorization: Typically only super-admins should see all comptes
        $this->authorize("viewAny", Compte::class);

        $comptes = Compte::withCount("dossiers")->paginate(15);
        return view("comptes.index", compact("comptes"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Authorization
        $this->authorize("create", Compte::class);
        return view("comptes.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Authorization
        $this->authorize("create", Compte::class);

        $validated = $request->validate([
            "nom" => "required|string|max:255",
            "identifiant_unique" => "required|string|max:255|unique:comptes,identifiant_unique",
            "adresse" => "nullable|string",
            "telephone" => "nullable|string|max:20",
            "email" => "nullable|email|max:255",
            "actif" => "boolean",
        ]);

        $validated["actif"] = $request->has("actif");

        Compte::create($validated);

        return redirect()->route("comptes.index")->with("success", "Compte créé avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Compte $compte): View
    {
        // Authorization
        $this->authorize("view", $compte);

        $compte->load("dossiers", "users"); // Load related data
        return view("comptes.show", compact("compte"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compte $compte): View
    {
        // Authorization
        $this->authorize("update", $compte);
        return view("comptes.edit", compact("compte"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Compte $compte): RedirectResponse
    {
        // Authorization
        $this->authorize("update", $compte);

        $validated = $request->validate([
            "nom" => "required|string|max:255",
            "identifiant_unique" => "required|string|max:255|unique:comptes,identifiant_unique," . $compte->id,
            "adresse" => "nullable|string",
            "telephone" => "nullable|string|max:20",
            "email" => "nullable|email|max:255",
            "actif" => "boolean",
        ]);

        $validated["actif"] = $request->has("actif");
        $compte->update($validated);

        return redirect()->route("comptes.index")->with("success", "Compte mis à jour avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compte $compte): RedirectResponse
    {
        // Authorization
        $this->authorize("delete", $compte);

        // Add checks: Cannot delete if it has dossiers?
        if ($compte->dossiers()->count() > 0) {
            return back()->with("error", "Impossible de supprimer un compte qui contient des dossiers.");
        }

        $compte->delete();

        return redirect()->route("comptes.index")->with("success", "Compte supprimé avec succès.");
    }
}
