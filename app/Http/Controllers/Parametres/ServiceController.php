<?php

namespace App\Http\Controllers\Parametres;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ServiceController extends Controller
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
        $services = Service::where("dossier_id", $dossierId)->with("site")->latest()->paginate(15);
        return view("parametres.services.index", compact("services"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $dossierId = $this->getCurrentDossierId();
        $sites = Site::where("dossier_id", $dossierId)->where("est_actif", 1)->orderBy("libelle")->get();
        return view("parametres.services.create", compact("sites"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $dossierId = $this->getCurrentDossierId();
        $request->validate([
            "code" => ["required", "string", "max:50", "unique:services,code,NULL,id,dossier_id,".$dossierId],
            "libelle" => ["required", "string", "max:255"],
            "site_id" => ["required", "exists:sites,id,dossier_id,".$dossierId], // Ensure site exists and belongs to the dossier
            "responsable" => ["nullable", "string", "max:255"],
            "email_responsable" => ["nullable", "email", "max:255"],
            "telephone_responsable" => ["nullable", "string", "max:20"],
            "est_actif" => ["nullable", "boolean"],
        ]);

        $data = $request->all();
        $data["dossier_id"] = $dossierId;
        $data["est_actif"] = $request->has("est_actif") ? 1 : 0;

        Service::create($data);

        return redirect()->route("parametres.services.index")
                         ->with("success", "Service créé avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service): View
    {
        if ($service->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }
        
        return view("parametres.services.show", compact("service"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service): View
    {
        if ($service->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }
        $dossierId = $this->getCurrentDossierId();
        $sites = Site::where("dossier_id", $dossierId)->where("est_actif", 1)->orderBy("libelle")->get();
        return view("parametres.services.edit", compact("service", "sites"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        if ($service->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        $dossierId = $this->getCurrentDossierId();
        $request->validate([
            "code" => ["required", "string", "max:50", "unique:services,code,".$service->id.",id,dossier_id,".$dossierId],
            "libelle" => ["required", "string", "max:255"],
            "site_id" => ["required", "exists:sites,id,dossier_id,".$dossierId],
            "responsable" => ["nullable", "string", "max:255"],
            "email_responsable" => ["nullable", "email", "max:255"],
            "telephone_responsable" => ["nullable", "string", "max:20"],
            "est_actif" => ["nullable", "boolean"],
        ]);

        $data = $request->all();
        $data["est_actif"] = $request->has("est_actif") ? 1 : 0;
        
        $service->update($data);

        return redirect()->route("parametres.services.index")
                         ->with("success", "Service mis à jour avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service): RedirectResponse
    {
        if ($service->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        try {
            // Vérification des dépendances
            if (method_exists($service, 'immobilisations') && $service->immobilisations()->exists()) {
                return redirect()->route("parametres.services.index")
                                ->with("error", "Impossible de supprimer le service car il est utilisé par des immobilisations.");
            }
            
            // Vérification d'autres dépendances potentielles
            if (method_exists($service, 'utilisateurs') && $service->utilisateurs()->exists()) {
                return redirect()->route("parametres.services.index")
                                ->with("error", "Impossible de supprimer le service car il est associé à des utilisateurs.");
            }

            $service->delete();
            return redirect()->route("parametres.services.index")
                             ->with("success", "Service supprimé avec succès.");
        } catch (\Exception $e) {
            logger()->error("Error deleting service {$service->id}: ".$e->getMessage());
            return redirect()->route("parametres.services.index")
                             ->with("error", "Erreur lors de la suppression du service. Il est peut-être utilisé par d'autres éléments.");
        }
    }
}
