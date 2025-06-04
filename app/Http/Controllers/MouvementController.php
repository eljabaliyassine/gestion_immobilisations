<?php

namespace App\Http\Controllers;

use App\Models\Immobilisation;
use App\Models\Mouvement;
use App\Models\Site;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MouvementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('dossier.selected');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dossier_id = session('dossier_id');
        $mouvements = Mouvement::whereHas('immobilisation', function($query) use ($dossier_id) {
                            $query->where('dossier_id', $dossier_id);
                        })
                        ->orderBy('date_mouvement', 'desc')
                        ->paginate(15);
        
        return view('mouvements.index', compact('mouvements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dossier_id = session('dossier_id');
        $immobilisations = Immobilisation::where('dossier_id', $dossier_id)
                                        ->orderBy('designation')
                                        ->get();
        $sites = Site::where('dossier_id', $dossier_id)->orderBy('libelle')->get();
        $services = Service::where('dossier_id', $dossier_id)->orderBy('libelle')->get();
        
        return view('mouvements.create', compact('immobilisations', 'sites', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dossier_id = session('dossier_id');
        
        $validator = Validator::make($request->all(), [
            'immobilisation_id' => 'required|exists:immobilisations,id',
            'type_mouvement' => 'required|in:entree,sortie,transfert',
            'date_mouvement' => 'required|date',
            'site_id' => 'required|exists:sites,id',
            'service_id' => 'required|exists:services,id',
            'commentaire' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('mouvements.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Vérifier que l'immobilisation appartient au dossier courant
        $immobilisation = Immobilisation::where('dossier_id', $dossier_id)
                                       ->findOrFail($request->immobilisation_id);
        
        // Vérifier que le site appartient au dossier courant
        $site = Site::where('dossier_id', $dossier_id)
                   ->findOrFail($request->site_id);
        
        // Vérifier que le service appartient au dossier courant
        $service = Service::where('dossier_id', $dossier_id)
                         ->findOrFail($request->service_id);
        
        // Créer le mouvement
        $mouvement = new Mouvement();
        $mouvement->immobilisation_id = $immobilisation->id;
        $mouvement->type_mouvement = $request->type_mouvement;
        $mouvement->date_mouvement = $request->date_mouvement;
        $mouvement->site_id = $site->id;
        $mouvement->service_id = $service->id;
        $mouvement->commentaire = $request->commentaire;
        $mouvement->user_id = Auth::id();
        $mouvement->save();
        
        // Mettre à jour la localisation de l'immobilisation
        $immobilisation->site_id = $site->id;
        $immobilisation->service_id = $service->id;
        $immobilisation->save();
        
        return redirect()->route('mouvements.show', $mouvement->id)
            ->with('success', 'Mouvement enregistré avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dossier_id = session('dossier_id');
        $mouvement = Mouvement::whereHas('immobilisation', function($query) use ($dossier_id) {
                            $query->where('dossier_id', $dossier_id);
                        })
                        ->findOrFail($id);
        
        return view('mouvements.show', compact('mouvement'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dossier_id = session('dossier_id');
        $mouvement = Mouvement::whereHas('immobilisation', function($query) use ($dossier_id) {
                            $query->where('dossier_id', $dossier_id);
                        })
                        ->findOrFail($id);
        
        $immobilisations = Immobilisation::where('dossier_id', $dossier_id)
                                        ->orderBy('designation')
                                        ->get();
        $sites = Site::where('dossier_id', $dossier_id)->orderBy('libelle')->get();
        $services = Service::where('dossier_id', $dossier_id)->orderBy('libelle')->get();
        
        return view('mouvements.edit', compact('mouvement', 'immobilisations', 'sites', 'services'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dossier_id = session('dossier_id');
        $mouvement = Mouvement::whereHas('immobilisation', function($query) use ($dossier_id) {
                            $query->where('dossier_id', $dossier_id);
                        })
                        ->findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'immobilisation_id' => 'required|exists:immobilisations,id',
            'type_mouvement' => 'required|in:entree,sortie,transfert',
            'date_mouvement' => 'required|date',
            'site_id' => 'required|exists:sites,id',
            'service_id' => 'required|exists:services,id',
            'commentaire' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('mouvements.edit', $mouvement->id)
                ->withErrors($validator)
                ->withInput();
        }

        // Vérifier que l'immobilisation appartient au dossier courant
        $immobilisation = Immobilisation::where('dossier_id', $dossier_id)
                                       ->findOrFail($request->immobilisation_id);
        
        // Vérifier que le site appartient au dossier courant
        $site = Site::where('dossier_id', $dossier_id)
                   ->findOrFail($request->site_id);
        
        // Vérifier que le service appartient au dossier courant
        $service = Service::where('dossier_id', $dossier_id)
                         ->findOrFail($request->service_id);
        
        // Mettre à jour le mouvement
        $mouvement->immobilisation_id = $immobilisation->id;
        $mouvement->type_mouvement = $request->type_mouvement;
        $mouvement->date_mouvement = $request->date_mouvement;
        $mouvement->site_id = $site->id;
        $mouvement->service_id = $service->id;
        $mouvement->commentaire = $request->commentaire;
        $mouvement->save();
        
        return redirect()->route('mouvements.show', $mouvement->id)
            ->with('success', 'Mouvement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dossier_id = session('dossier_id');
        $mouvement = Mouvement::whereHas('immobilisation', function($query) use ($dossier_id) {
                            $query->where('dossier_id', $dossier_id);
                        })
                        ->findOrFail($id);
        
        // Vérifier si c'est le dernier mouvement de l'immobilisation
        $dernierMouvement = Mouvement::where('immobilisation_id', $mouvement->immobilisation_id)
                                    ->orderBy('date_mouvement', 'desc')
                                    ->first();
        
        if ($dernierMouvement->id == $mouvement->id) {
            return redirect()->route('mouvements.show', $mouvement->id)
                ->with('error', 'Impossible de supprimer le dernier mouvement d\'une immobilisation.');
        }
        
        $mouvement->delete();
        
        return redirect()->route('mouvements.index')
            ->with('success', 'Mouvement supprimé avec succès.');
    }
}
