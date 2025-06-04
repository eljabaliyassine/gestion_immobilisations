<?php

namespace App\Http\Controllers\Parametres;

use App\Http\Controllers\Controller;
use App\Models\Prestataire;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PrestataireController extends Controller
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
        $prestataires = Prestataire::where("dossier_id", $dossierId)->latest()->paginate(15);
        return view("parametres.prestataires.index", compact("prestataires"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view("parametres.prestataires.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $dossierId = $this->getCurrentDossierId();
        $request->validate([
            "code" => ["required", "string", "max:50", "unique:prestataires,code,NULL,id,dossier_id,".$dossierId],
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

        Prestataire::create($data);

        return redirect()->route("parametres.prestataires.index")
                         ->with("success", "Prestataire créé avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Prestataire $prestataire): View
    {
        if ($prestataire->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }
        
        return view("parametres.prestataires.show", compact("prestataire"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prestataire $prestataire): View
    {
        if ($prestataire->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }
        return view("parametres.prestataires.edit", compact("prestataire"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prestataire $prestataire): RedirectResponse
    {
        if ($prestataire->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        $dossierId = $this->getCurrentDossierId();
        $request->validate([
            "code" => ["required", "string", "max:50", "unique:prestataires,code,".$prestataire->id.",id,dossier_id,".$dossierId],
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
        
        $prestataire->update($data);

        return redirect()->route("parametres.prestataires.index")
                         ->with("success", "Prestataire mis à jour avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prestataire $prestataire): RedirectResponse
    {
        if ($prestataire->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        try {
            // Vérification des dépendances
            if (method_exists($prestataire, 'maintenances') && $prestataire->maintenances()->exists()) {
                return redirect()->route("parametres.prestataires.index")
                                ->with("error", "Impossible de supprimer le prestataire car il est lié à des maintenances.");
            }
            
            // Vérification d'autres dépendances potentielles
            if (method_exists($prestataire, 'contrats') && $prestataire->contrats()->exists()) {
                return redirect()->route("parametres.prestataires.index")
                                ->with("error", "Impossible de supprimer le prestataire car il est lié à des contrats.");
            }

            $prestataire->delete();
            return redirect()->route("parametres.prestataires.index")
                             ->with("success", "Prestataire supprimé avec succès.");
        } catch (\Exception $e) {
            logger()->error("Error deleting prestataire {$prestataire->id}: ".$e->getMessage());
            return redirect()->route("parametres.prestataires.index")
                             ->with("error", "Erreur lors de la suppression du prestataire. Il est peut-être utilisé par d'autres éléments.");
        }
    }
}
