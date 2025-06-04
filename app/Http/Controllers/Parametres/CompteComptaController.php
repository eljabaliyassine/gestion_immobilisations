<?php

namespace App\Http\Controllers\Parametres;

use App\Http\Controllers\Controller;
use App\Models\CompteCompta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CompteComptaController extends Controller
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
        $comptescompta = CompteCompta::where('dossier_id', $dossierId)
            ->orderBy('numero')
            ->paginate(15);
            
        return view('parametres.comptescompta.index', compact('comptescompta'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('parametres.comptescompta.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $dossierId = $this->getCurrentDossierId();
        $request->validate([
            'numero' => 'required|string|max:10|unique:comptescompta,numero,NULL,id,dossier_id,'.$dossierId,
            'libelle' => 'required|string|max:100',
            'type' => 'required|string|max:50',
            'est_actif' => 'nullable|boolean',
        ]);
        
        $comptecompta = new CompteCompta();
        $comptecompta->numero = $request->numero;
        $comptecompta->libelle = $request->libelle;
        $comptecompta->type = $request->type;
        $comptecompta->est_actif = $request->has('est_actif') ? 1 : 0;
        $comptecompta->dossier_id = $dossierId;
        $comptecompta->save();
        
        return redirect()->route('parametres.comptescompta.index')
            ->with('success', 'Compte comptable créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CompteCompta $comptescomptum): View
    {
        // Vérifier que le compte comptable appartient au dossier courant
        if ($comptescomptum->dossier_id != $this->getCurrentDossierId()) {
            abort(403, 'Accès non autorisé à ce compte comptable.');
        }
        
        return view('parametres.comptescompta.show', ['comptecompta' => $comptescomptum]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompteCompta $comptescomptum): View
    {
        // Vérifier que le compte comptable appartient au dossier courant
        if ($comptescomptum->dossier_id != $this->getCurrentDossierId()) {
            abort(403, 'Accès non autorisé à ce compte comptable.');
        }
        
        return view('parametres.comptescompta.edit', ['comptecompta' => $comptescomptum]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompteCompta $comptescomptum): RedirectResponse
    {
        // Vérifier que le compte comptable appartient au dossier courant
        if ($comptescomptum->dossier_id != $this->getCurrentDossierId()) {
            abort(403, 'Accès non autorisé à ce compte comptable.');
        }
        
        $dossierId = $this->getCurrentDossierId();
        $request->validate([
            'numero' => 'required|string|max:10|unique:comptescompta,numero,'.$comptescomptum->id.',id,dossier_id,'.$dossierId,
            'libelle' => 'required|string|max:100',
            'type' => 'required|string|max:50',
            'est_actif' => 'nullable|boolean',
        ]);
        
        $comptescomptum->numero = $request->numero;
        $comptescomptum->libelle = $request->libelle;
        $comptescomptum->type = $request->type;
        $comptescomptum->est_actif = $request->has('est_actif') ? 1 : 0;
        $comptescomptum->save();
        
        return redirect()->route('parametres.comptescompta.index')
            ->with('success', 'Compte comptable mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompteCompta $comptescomptum): RedirectResponse
    {
        // Vérifier que le compte comptable appartient au dossier courant
        if ($comptescomptum->dossier_id != $this->getCurrentDossierId()) {
            abort(403, 'Accès non autorisé à ce compte comptable.');
        }
        
        try {
            // Vérifier si le compte est utilisé par des immobilisations
            if (method_exists($comptescomptum, 'immobilisations') && $comptescomptum->immobilisations()->exists()) {
                return redirect()->route('parametres.comptescompta.index')
                    ->with('error', 'Ce compte comptable ne peut pas être supprimé car il est utilisé par des immobilisations.');
            }
            
            // Vérifier si le compte est utilisé par des familles
            if (method_exists($comptescomptum, 'famillesImmobilisation') && $comptescomptum->famillesImmobilisation()->exists()) {
                return redirect()->route('parametres.comptescompta.index')
                    ->with('error', 'Ce compte comptable ne peut pas être supprimé car il est utilisé comme compte d\'immobilisation par des familles.');
            }
            
            if (method_exists($comptescomptum, 'famillesAmortissement') && $comptescomptum->famillesAmortissement()->exists()) {
                return redirect()->route('parametres.comptescompta.index')
                    ->with('error', 'Ce compte comptable ne peut pas être supprimé car il est utilisé comme compte d\'amortissement par des familles.');
            }
            
            if (method_exists($comptescomptum, 'famillesDotation') && $comptescomptum->famillesDotation()->exists()) {
                return redirect()->route('parametres.comptescompta.index')
                    ->with('error', 'Ce compte comptable ne peut pas être supprimé car il est utilisé comme compte de dotation par des familles.');
            }
            
            $comptescomptum->delete();
            
            return redirect()->route('parametres.comptescompta.index')
                ->with('success', 'Compte comptable supprimé avec succès.');
        } catch (\Exception $e) {
            logger()->error("Error deleting compte comptable {$comptescomptum->id}: ".$e->getMessage());
            return redirect()->route('parametres.comptescompta.index')
                ->with('error', 'Erreur lors de la suppression du compte comptable. Il est peut-être utilisé par d\'autres éléments.');
        }
    }
}
