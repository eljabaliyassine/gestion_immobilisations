<?php

namespace App\Http\Controllers\Parametres;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SiteController extends Controller
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
        $sites = Site::where("dossier_id", $dossierId)->latest()->paginate(15);
        return view("parametres.sites.index", compact("sites"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view("parametres.sites.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $dossierId = $this->getCurrentDossierId();
        $request->validate([
            "code" => ["required", "string", "max:50", "unique:sites,code,NULL,id,dossier_id,".$dossierId],
            "libelle" => ["required", "string", "max:255"],
            "adresse" => ["nullable", "string"],
            "code_postal" => ["nullable", "string", "max:10"],
            "ville" => ["nullable", "string", "max:100"],
            "pays" => ["nullable", "string", "max:100"],
            "telephone" => ["nullable", "string", "max:20"],
            "email" => ["nullable", "email", "max:255"],
            "est_actif" => ["nullable", "boolean"],
        ]);

        $data = $request->all();
        $data["dossier_id"] = $dossierId;
        $data["est_actif"] = $request->has("est_actif") ? 1 : 0;

        Site::create($data);

        return redirect()->route("parametres.sites.index")
                         ->with("success", "Site créé avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Site $site): View
    {
        if ($site->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }
        
        return view("parametres.sites.show", compact("site"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Site $site): View
    {
        if ($site->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }
        return view("parametres.sites.edit", compact("site"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Site $site): RedirectResponse
    {
        if ($site->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        $dossierId = $this->getCurrentDossierId();
        $request->validate([
            "code" => ["required", "string", "max:50", "unique:sites,code,".$site->id.",id,dossier_id,".$dossierId],
            "libelle" => ["required", "string", "max:255"],
            "adresse" => ["nullable", "string"],
            "code_postal" => ["nullable", "string", "max:10"],
            "ville" => ["nullable", "string", "max:100"],
            "pays" => ["nullable", "string", "max:100"],
            "telephone" => ["nullable", "string", "max:20"],
            "email" => ["nullable", "email", "max:255"],
            "est_actif" => ["nullable", "boolean"],
        ]);

        $data = $request->all();
        $data["est_actif"] = $request->has("est_actif") ? 1 : 0;
        
        $site->update($data);

        return redirect()->route("parametres.sites.index")
                         ->with("success", "Site mis à jour avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site): RedirectResponse
    {
        if ($site->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        try {
            // Vérification des dépendances
            if (method_exists($site, 'services') && $site->services()->exists()) {
                return redirect()->route("parametres.sites.index")
                                ->with("error", "Impossible de supprimer le site car il est utilisé par des services.");
            }
            
            // Vérification d'autres dépendances potentielles
            if (method_exists($site, 'immobilisations') && $site->immobilisations()->exists()) {
                return redirect()->route("parametres.sites.index")
                                ->with("error", "Impossible de supprimer le site car il est lié à des immobilisations.");
            }

            $site->delete();
            return redirect()->route("parametres.sites.index")
                             ->with("success", "Site supprimé avec succès.");
        } catch (\Exception $e) {
            logger()->error("Error deleting site {$site->id}: ".$e->getMessage());
            return redirect()->route("parametres.sites.index")
                             ->with("error", "Erreur lors de la suppression du site. Il est peut-être utilisé par d'autres éléments.");
        }
    }
}
