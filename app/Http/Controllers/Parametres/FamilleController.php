<?php

namespace App\Http\Controllers\Parametres;

use App\Http\Controllers\Controller;
use App\Models\Famille;
use App\Models\CompteCompta;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class FamilleController extends Controller
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
        $familles = Famille::where("dossier_id", $dossierId)
            ->with(['compteImmobilisation', 'compteAmortissement', 'compteDotation'])
            ->latest()
            ->paginate(15);
        return view("parametres.familles.index", compact("familles"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $dossierId = $this->getCurrentDossierId();
        
        // Récupérer tous les comptes comptables du dossier courant
        $comptesCompta = CompteCompta::where("dossier_id", $dossierId)
            ->orderBy('numero')
            ->get();
            
        return view("parametres.familles.create", compact("comptesCompta"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $dossierId = $this->getCurrentDossierId();
        $request->validate([
            "code" => ["required", "string", "max:50", "unique:familles,code,NULL,id,dossier_id,".$dossierId],
            "libelle" => ["required", "string", "max:255"],
            "comptecompta_immobilisation_id" => ["required", "exists:comptescompta,id,dossier_id,".$dossierId],
            "comptecompta_amortissement_id" => ["required", "exists:comptescompta,id,dossier_id,".$dossierId],
            "comptecompta_dotation_id" => ["required", "exists:comptescompta,id,dossier_id,".$dossierId],
            "duree_amortissement_par_defaut" => ["nullable", "integer", "min:1", "max:50"],
            "methode_amortissement_par_defaut" => ["nullable", "string", "in:lineaire,degressif"],
        ]);

        $data = $request->all();
        $data["dossier_id"] = $dossierId;

        Famille::create($data);

        return redirect()->route("parametres.familles.index")
                         ->with("success", "Famille créée avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Famille $famille): View
    {
        // Ensure the famille belongs to the current dossier
        if ($famille->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }
        
        $famille->load(['compteImmobilisation', 'compteAmortissement', 'compteDotation']);
        
        return view("parametres.familles.show", compact("famille"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Famille $famille): View
    {
        if ($famille->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }
        
        $dossierId = $this->getCurrentDossierId();
        
        // Récupérer tous les comptes comptables du dossier courant
        $comptesCompta = CompteCompta::where("dossier_id", $dossierId)
            ->orderBy('numero')
            ->get();
            
        return view("parametres.familles.edit", compact("famille", "comptesCompta"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Famille $famille): RedirectResponse
    {
        if ($famille->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        $dossierId = $this->getCurrentDossierId();
        $request->validate([
            "code" => ["required", "string", "max:50", "unique:familles,code,".$famille->id.",id,dossier_id,".$dossierId],
            "libelle" => ["required", "string", "max:255"],
            "comptecompta_immobilisation_id" => ["required", "exists:comptescompta,id,dossier_id,".$dossierId],
            "comptecompta_amortissement_id" => ["required", "exists:comptescompta,id,dossier_id,".$dossierId],
            "comptecompta_dotation_id" => ["required", "exists:comptescompta,id,dossier_id,".$dossierId],
            "duree_amortissement_par_defaut" => ["nullable", "integer", "min:1", "max:50"],
            "methode_amortissement_par_defaut" => ["nullable", "string", "in:lineaire,degressif"],
        ]);

        $famille->update($request->all());

        return redirect()->route("parametres.familles.index")
                         ->with("success", "Famille mise à jour avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Famille $famille): RedirectResponse
    {
        if ($famille->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        try {
            // Vérifier si la famille est utilisée par des immobilisations
            if (method_exists($famille, 'immobilisations') && $famille->immobilisations()->exists()) {
                return redirect()->route("parametres.familles.index")
                                ->with("error", "Impossible de supprimer cette famille car elle est utilisée par des immobilisations.");
            }
            
            $famille->delete();
            return redirect()->route("parametres.familles.index")
                             ->with("success", "Famille supprimée avec succès.");
        } catch (\Exception $e) {
            // Log error
            logger()->error("Error deleting famille {$famille->id}: ".$e->getMessage());
            return redirect()->route("parametres.familles.index")
                             ->with("error", "Erreur lors de la suppression de la famille. Elle est peut-être utilisée.");
        }
    }
}
