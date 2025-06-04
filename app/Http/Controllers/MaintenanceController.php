<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Immobilisation;
use App\Models\Prestataire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maintenances = Maintenance::with(['immobilisation', 'prestataire', 'user'])
            ->whereHas('immobilisation', function ($query) {
                $query->where('dossier_id', $this->getCurrentDossierId());
            })
            ->orderBy('date_intervention', 'desc')
            ->paginate(15);
            
        return view('maintenances.index', compact('maintenances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $immobilisations = Immobilisation::where('dossier_id', $this->getCurrentDossierId())
            ->where('statut', 'actif')
            ->orderBy('libelle')
            ->get();
            
        $prestataires = Prestataire::where('dossier_id', $this->getCurrentDossierId())
            ->where('est_actif', true)
            ->orderBy('nom')
            ->get();
            
        return view('maintenances.create', compact('immobilisations', 'prestataires'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'immobilisation_id' => 'required|exists:immobilisations,id',
            'date_intervention' => 'required|date',
            'type' => 'required|string|max:50',
            'description' => 'required|string|max:1000',
            'prestataire_id' => 'nullable|exists:prestataires,id',
            'cout' => 'required|numeric|min:0',
            'est_charge' => 'required|boolean',
            'date_fin_intervention' => 'nullable|date|after_or_equal:date_intervention',
        ]);
        
        // Vérifier que l'immobilisation appartient au dossier courant
        $immobilisation = Immobilisation::where('id', $request->immobilisation_id)
            ->where('dossier_id', $this->getCurrentDossierId())
            ->firstOrFail();
        
        // Vérifier que le prestataire appartient au dossier courant si fourni
        if ($request->prestataire_id) {
            $prestataire = Prestataire::where('id', $request->prestataire_id)
                ->where('dossier_id', $this->getCurrentDossierId())
                ->firstOrFail();
        }
        
        $maintenance = new Maintenance();
        $maintenance->immobilisation_id = $request->immobilisation_id;
        $maintenance->date_intervention = $request->date_intervention;
        $maintenance->type = $request->type;
        $maintenance->description = $request->description;
        $maintenance->prestataire_id = $request->prestataire_id;
        $maintenance->cout = $request->cout;
        $maintenance->est_charge = $request->est_charge;
        $maintenance->date_fin_intervention = $request->date_fin_intervention;
        $maintenance->user_id = Auth::id();
        $maintenance->save();
        
        return redirect()->route('maintenances.show', $maintenance->id)
            ->with('success', 'Maintenance créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Maintenance $maintenance)
    {
        // Vérifier que la maintenance appartient au dossier courant
        if ($maintenance->immobilisation->dossier_id != $this->getCurrentDossierId()) {
            abort(403, 'Accès non autorisé à cette maintenance.');
        }
        
        return view('maintenances.show', compact('maintenance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maintenance $maintenance)
    {
        // Vérifier que la maintenance appartient au dossier courant
        if ($maintenance->immobilisation->dossier_id != $this->getCurrentDossierId()) {
            abort(403, 'Accès non autorisé à cette maintenance.');
        }
        
        $immobilisations = Immobilisation::where('dossier_id', $this->getCurrentDossierId())
            ->orderBy('libelle')
            ->get();
            
        $prestataires = Prestataire::where('dossier_id', $this->getCurrentDossierId())
            ->where('est_actif', true)
            ->orderBy('nom')
            ->get();
            
        return view('maintenances.edit', compact('maintenance', 'immobilisations', 'prestataires'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        // Vérifier que la maintenance appartient au dossier courant
        if ($maintenance->immobilisation->dossier_id != $this->getCurrentDossierId()) {
            abort(403, 'Accès non autorisé à cette maintenance.');
        }
        
        $request->validate([
            'immobilisation_id' => 'required|exists:immobilisations,id',
            'date_intervention' => 'required|date',
            'type' => 'required|string|max:50',
            'description' => 'required|string|max:1000',
            'prestataire_id' => 'nullable|exists:prestataires,id',
            'cout' => 'required|numeric|min:0',
            'est_charge' => 'required|boolean',
            'date_fin_intervention' => 'nullable|date|after_or_equal:date_intervention',
        ]);
        
        // Vérifier que l'immobilisation appartient au dossier courant
        $immobilisation = Immobilisation::where('id', $request->immobilisation_id)
            ->where('dossier_id', $this->getCurrentDossierId())
            ->firstOrFail();
        
        // Vérifier que le prestataire appartient au dossier courant si fourni
        if ($request->prestataire_id) {
            $prestataire = Prestataire::where('id', $request->prestataire_id)
                ->where('dossier_id', $this->getCurrentDossierId())
                ->firstOrFail();
        }
        
        $maintenance->immobilisation_id = $request->immobilisation_id;
        $maintenance->date_intervention = $request->date_intervention;
        $maintenance->type = $request->type;
        $maintenance->description = $request->description;
        $maintenance->prestataire_id = $request->prestataire_id;
        $maintenance->cout = $request->cout;
        $maintenance->est_charge = $request->est_charge;
        $maintenance->date_fin_intervention = $request->date_fin_intervention;
        $maintenance->save();
        
        return redirect()->route('maintenances.show', $maintenance->id)
            ->with('success', 'Maintenance mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        // Vérifier que la maintenance appartient au dossier courant
        if ($maintenance->immobilisation->dossier_id != $this->getCurrentDossierId()) {
            abort(403, 'Accès non autorisé à cette maintenance.');
        }
        
        $maintenance->delete();
        
        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance supprimée avec succès.');
    }
    
    /**
     * Récupère l'ID du dossier courant de manière cohérente.
     * Vérifie à la fois session("current_dossier_id") et session("dossier_id").
     */
    private function getCurrentDossierId()
    {
        return session("current_dossier_id") ?? session("dossier_id");
    }
}
