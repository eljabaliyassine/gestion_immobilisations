<?php

namespace App\Http\Controllers\Parametres;

use App\Http\Controllers\Controller;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FournisseurController extends Controller
{
    /**
     * Récupère l'ID du dossier courant depuis la session
     */
    private function getCurrentDossierId()
    {
        return session("current_dossier_id") ?? session("dossier_id");
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $dossierId = $this->getCurrentDossierId();
        $fournisseurs = Fournisseur::where("dossier_id", $dossierId)->latest()->paginate(15);
        return view("parametres.fournisseurs.index", compact("fournisseurs"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view("parametres.fournisseurs.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $dossierId = $this->getCurrentDossierId();
        $request->validate([
            "code" => ["required", "string", "max:50", "unique:fournisseurs,code,NULL,id,dossier_id,".$dossierId],
            "nom" => ["required", "string", "max:255"],
            "adresse" => ["nullable", "string"],
            "code_postal" => ["nullable", "string", "max:10"],
            "ville" => ["nullable", "string", "max:100"],
            "pays" => ["nullable", "string", "max:100"],
            "telephone" => ["nullable", "string", "max:20"],
            "email" => ["nullable", "email", "max:255"],
            "site_web" => ["nullable", "url", "max:255"],
            "siret" => ["nullable", "string", "max:20"],
            "contact_nom" => ["nullable", "string", "max:255"],
            "contact_telephone" => ["nullable", "string", "max:20"],
            "contact_email" => ["nullable", "email", "max:255"],
            "est_actif" => ["nullable", "boolean"],
        ]);

        $data = $request->all();
        $data["dossier_id"] = $dossierId;
        $data["est_actif"] = $request->has("est_actif") ? 1 : 0;

        Fournisseur::create($data);

        return redirect()->route("parametres.fournisseurs.index")
                         ->with("success", "Fournisseur créé avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Fournisseur $fournisseur): View
    {
        if ($fournisseur->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }
        
        return view("parametres.fournisseurs.show", compact("fournisseur"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fournisseur $fournisseur): View
    {
        if ($fournisseur->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }
        return view("parametres.fournisseurs.edit", compact("fournisseur"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fournisseur $fournisseur): RedirectResponse
    {
        if ($fournisseur->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        $dossierId = $this->getCurrentDossierId();
        $request->validate([
            "code" => ["required", "string", "max:50", "unique:fournisseurs,code,".$fournisseur->id.",id,dossier_id,".$dossierId],
            "nom" => ["required", "string", "max:255"],
            "adresse" => ["nullable", "string"],
            "code_postal" => ["nullable", "string", "max:10"],
            "ville" => ["nullable", "string", "max:100"],
            "pays" => ["nullable", "string", "max:100"],
            "telephone" => ["nullable", "string", "max:20"],
            "email" => ["nullable", "email", "max:255"],
            "site_web" => ["nullable", "url", "max:255"],
            "siret" => ["nullable", "string", "max:20"],
            "contact_nom" => ["nullable", "string", "max:255"],
            "contact_telephone" => ["nullable", "string", "max:20"],
            "contact_email" => ["nullable", "email", "max:255"],
            "est_actif" => ["nullable", "boolean"],
        ]);

        $data = $request->all();
        $data["est_actif"] = $request->has("est_actif") ? 1 : 0;
        
        $fournisseur->update($data);

        return redirect()->route("parametres.fournisseurs.index")
                         ->with("success", "Fournisseur mis à jour avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fournisseur $fournisseur): RedirectResponse
    {
        if ($fournisseur->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        try {
            // Vérification des dépendances
            if (method_exists($fournisseur, 'immobilisations') && $fournisseur->immobilisations()->exists()) {
                return redirect()->route("parametres.fournisseurs.index")
                                ->with("error", "Impossible de supprimer le fournisseur car il est lié à des immobilisations.");
            }
            
            // Vérification d'autres dépendances potentielles
            if (method_exists($fournisseur, 'factures') && $fournisseur->factures()->exists()) {
                return redirect()->route("parametres.fournisseurs.index")
                                ->with("error", "Impossible de supprimer le fournisseur car il est lié à des factures.");
            }

            $fournisseur->delete();
            return redirect()->route("parametres.fournisseurs.index")
                             ->with("success", "Fournisseur supprimé avec succès.");
        } catch (\Exception $e) {
            logger()->error("Error deleting fournisseur {$fournisseur->id}: ".$e->getMessage());
            return redirect()->route("parametres.fournisseurs.index")
                             ->with("error", "Erreur lors de la suppression du fournisseur. Il est peut-être utilisé par d'autres éléments.");
        }
    }
}
