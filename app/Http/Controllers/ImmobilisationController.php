<?php

namespace App\Http\Controllers;

use App\Models\Immobilisation;
use App\Models\Famille;
use App\Models\Site;
use App\Models\Service;
use App\Models\Fournisseur;
use App\Models\CompteCompta;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImmobilisationsImport;
use App\Exports\ImmobilisationsExport;
use Illuminate\Support\Facades\Log;

class ImmobilisationController extends Controller
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
     * Récupère l'ID du dossier courant depuis la session.
     *
     * @return int|null
     */
    protected function getCurrentDossierId()
    {
        return session('current_dossier_id') ?? session('dossier_id');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $dossierId = $this->getCurrentDossierId();
        $query = Immobilisation::where("dossier_id", $dossierId)->with(["famille", "site", "service"]);

        // Apply filters
        if ($request->filled("filter_code_barre")) {
            $query->where("code_barre", "like", "%" . $request->input("filter_code_barre") . "%");
        }
        if ($request->filled("filter_designation")) {
            $query->where("designation", "like", "%" . $request->input("filter_designation") . "%");
        }
        if ($request->filled("filter_famille_id")) {
            $query->where("famille_id", $request->input("filter_famille_id"));
        }
        if ($request->filled("filter_site_id")) {
            $query->where("site_id", $request->input("filter_site_id"));
        }
        if ($request->filled("filter_service_id")) {
            $query->where("service_id", $request->input("filter_service_id"));
        }
        // Add other filters here if needed

        $immobilisations = $query->latest()->paginate(15);

        // Data for filters
        $familles = Famille::where("dossier_id", $dossierId)->orderBy("libelle")->get();
        $sites = Site::where("dossier_id", $dossierId)->orderBy("libelle")->get();
        // Services might need dynamic loading or pre-loading based on selected site filter
        $services = Service::where("dossier_id", $dossierId)->orderBy("libelle")->get(); // Load all for now

        return view("immobilisations.index", compact("immobilisations", "familles", "sites", "services"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $dossierId = $this->getCurrentDossierId();
        $familles = Famille::where("dossier_id", $dossierId)->orderBy("libelle")->get();
        $sites = Site::where("dossier_id", $dossierId)->orderBy("libelle")->get();
        $fournisseurs = Fournisseur::where("dossier_id", $dossierId)->orderBy("nom")->get();
        $comptescompta = CompteCompta::where("dossier_id", $dossierId)->orderBy("numero")->get();
        
        // Services will be loaded dynamically via JS/API, but we preload them for the current dossier
        $services = Service::where("dossier_id", $dossierId)->orderBy("libelle")->get();

        return view("immobilisations.create", compact("familles", "sites", "fournisseurs", "services", "comptescompta"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $dossierId = $this->getCurrentDossierId();

        $validatedData = $request->validate([
            "code_barre" => ["nullable", "string", "max:100", Rule::unique("immobilisations")->where("dossier_id", $dossierId)],
            "designation" => ["required", "string", "max:255"],
            "description" => ["nullable", "string"],
            "famille_id" => ["required", "exists:familles,id,dossier_id,".$dossierId],
            "site_id" => ["required", "exists:sites,id,dossier_id,".$dossierId],
            "service_id" => ["required", Rule::exists("services", "id")->where(function ($query) use ($request, $dossierId) {
                $query->where("dossier_id", $dossierId)
                      ->where("site_id", $request->input("site_id"));
            })],
            "date_acquisition" => ["required", "date"],
            "date_mise_service" => ["nullable", "date", "after_or_equal:date_acquisition"],
            "valeur_acquisition" => ["required", "numeric", "min:0"],
            "tva_deductible" => ["nullable", "numeric", "min:0"],
            "comptecompta_tva_id" => ["nullable", "exists:comptescompta,id,dossier_id,".$dossierId],
            "base_amortissement" => ["required", "numeric", "min:0"],
            "statut" => ["required", Rule::in(["En service", "En stock", "En réparation", "Cédé", "Rebut"])],
            "fournisseur_id" => ["nullable", "exists:fournisseurs,id,dossier_id,".$dossierId],
            "numero_facture" => ["nullable", "string", "max:100"],
            "numero_serie" => ["nullable", "string", "max:100"],
            "photo" => ["nullable", "image", "max:2048"], // Max 2MB
            "document_path" => ["nullable", "file", "mimes:pdf,doc,docx", "max:10240"], // Max 10MB
        ]);

        $validatedData["dossier_id"] = $dossierId;

        // Récupérer les valeurs de la famille sélectionnée pour les champs hérités
        $famille = Famille::findOrFail($request->input('famille_id'));
        $validatedData["comptecompta_immobilisation_id"] = $famille->comptecompta_immobilisation_id;
        $validatedData["comptecompta_amortissement_id"] = $famille->comptecompta_amortissement_id;
        $validatedData["comptecompta_dotation_id"] = $famille->comptecompta_dotation_id;
        $validatedData["duree_amortissement"] = $famille->duree_amortissement_par_defaut;
        $validatedData["methode_amortissement"] = $famille->methode_amortissement_par_defaut;
        
        // Mapper le statut "En service" à "actif" pour la base de données
        if ($validatedData["statut"] === "En service") {
            $validatedData["statut"] = "actif";
        }

        // Handle photo upload
        if ($request->hasFile("photo")) {
            $validatedData["photo_path"] = $request->file("photo")->store("immobilisations_photos/dossier_" . $dossierId, "public");
        }

        // Handle document upload
        if ($request->hasFile("document_path")) {
            $validatedData["document_path"] = $request->file("document_path")->store("immobilisations_documents/dossier_" . $dossierId, "public");
        }

        $immobilisation = Immobilisation::create($validatedData);

        // Créer le plan d'amortissement initial
        $this->createInitialAmortizationPlan($immobilisation);

        return redirect()->route("immobilisations.index")
                         ->with("success", "Immobilisation créée avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Immobilisation $immobilisation): View
    {
        if ($immobilisation->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }
        // Eager load relationships needed for the view
        $immobilisation->load([
            "famille", 
            "site", 
            "service", 
            "fournisseur", 
            "planAmortissements", 
            "mouvements", 
            "maintenances", 
            "contrats",
            "compteImmobilisation",
            "compteAmortissement",
            "compteDotation"
        ]);

        return view("immobilisations.show", compact("immobilisation"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Immobilisation $immobilisation): View
    {
        if ($immobilisation->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        $dossierId = $this->getCurrentDossierId();
        $familles = Famille::where("dossier_id", $dossierId)->orderBy("libelle")->get();
        $sites = Site::where("dossier_id", $dossierId)->orderBy("libelle")->get();
        $fournisseurs = Fournisseur::where("dossier_id", $dossierId)->orderBy("nom")->get();
        // Load services for the current dossier
        $services = Service::where("dossier_id", $dossierId)->orderBy("libelle")->get();
        $comptescompta = CompteCompta::where("dossier_id", $dossierId)->orderBy("numero")->get();

        // Mapper le statut "actif" à "En service" pour l'affichage
        if ($immobilisation->statut === "actif") {
            $immobilisation->statut = "En service";
        }

        return view("immobilisations.edit", compact("immobilisation", "familles", "sites", "fournisseurs", "services", "comptescompta"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Immobilisation $immobilisation): RedirectResponse
    {
        if ($immobilisation->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        $dossierId = $this->getCurrentDossierId();

        $validatedData = $request->validate([
            "code_barre" => ["nullable", "string", "max:100", Rule::unique("immobilisations")->where("dossier_id", $dossierId)->ignore($immobilisation->id)],
            "designation" => ["required", "string", "max:255"],
            "description" => ["nullable", "string"],
            "famille_id" => ["required", "exists:familles,id,dossier_id,".$dossierId],
            "site_id" => ["required", "exists:sites,id,dossier_id,".$dossierId],
            "service_id" => ["required", Rule::exists("services", "id")->where(function ($query) use ($request, $dossierId) {
                $query->where("dossier_id", $dossierId)
                      ->where("site_id", $request->input("site_id"));
            })],
            "date_acquisition" => ["required", "date"],
            "date_mise_service" => ["nullable", "date", "after_or_equal:date_acquisition"],
            "valeur_acquisition" => ["required", "numeric", "min:0"],
            "tva_deductible" => ["nullable", "numeric", "min:0"],
            "comptecompta_tva_id" => ["nullable", "exists:comptescompta,id,dossier_id,".$dossierId],
            "base_amortissement" => ["required", "numeric", "min:0"],
            "statut" => ["required", Rule::in(["En service", "En stock", "En réparation", "Cédé", "Rebut"])],
            "fournisseur_id" => ["nullable", "exists:fournisseurs,id,dossier_id,".$dossierId],
            "numero_facture" => ["nullable", "string", "max:100"],
            "numero_serie" => ["nullable", "string", "max:100"],
            "photo" => ["nullable", "image", "max:2048"], // Max 2MB
            "document_path" => ["nullable", "file", "mimes:pdf,doc,docx", "max:10240"], // Max 10MB
        ]);

        // Si la famille a changé, mettre à jour les champs hérités
        if ($immobilisation->famille_id != $request->input('famille_id')) {
            $famille = Famille::findOrFail($request->input('famille_id'));
            $validatedData["comptecompta_immobilisation_id"] = $famille->comptecompta_immobilisation_id;
            $validatedData["comptecompta_amortissement_id"] = $famille->comptecompta_amortissement_id;
            $validatedData["comptecompta_dotation_id"] = $famille->comptecompta_dotation_id;
            $validatedData["duree_amortissement"] = $famille->duree_amortissement_par_defaut;
            $validatedData["methode_amortissement"] = $famille->methode_amortissement_par_defaut;
        } else {
            // Conserver les valeurs existantes pour les champs hérités
            $validatedData["comptecompta_immobilisation_id"] = $immobilisation->comptecompta_immobilisation_id;
            $validatedData["comptecompta_amortissement_id"] = $immobilisation->comptecompta_amortissement_id;
            $validatedData["comptecompta_dotation_id"] = $immobilisation->comptecompta_dotation_id;
            $validatedData["duree_amortissement"] = $immobilisation->duree_amortissement;
            $validatedData["methode_amortissement"] = $immobilisation->methode_amortissement;
        }
        
        // Mapper le statut "En service" à "actif" pour la base de données
        if ($validatedData["statut"] === "En service") {
            $validatedData["statut"] = "actif";
        }

        // Handle photo upload and deletion of old photo
        if ($request->hasFile("photo")) {
            // Delete old photo if it exists
            if ($immobilisation->photo_path) {
                Storage::disk("public")->delete($immobilisation->photo_path);
            }
            // Store new photo
            $validatedData["photo_path"] = $request->file("photo")->store("immobilisations_photos/dossier_" . $dossierId, "public");
        }

        // Handle document upload and deletion of old document
        if ($request->hasFile("document_path")) {
            // Delete old document if it exists
            if ($immobilisation->document_path) {
                Storage::disk("public")->delete($immobilisation->document_path);
            }
            // Store new document
            $validatedData["document_path"] = $request->file("document_path")->store("immobilisations_documents/dossier_" . $dossierId, "public");
        }

        // TODO: Handle status change logic (e.g., Cédé, Rebut) -> create MouvementImmobilisation?
        $oldStatut = $immobilisation->statut;
        $newStatut = $validatedData["statut"];

        $immobilisation->update($validatedData);

        // If status changed to Cédé or Rebut, potentially trigger movement creation
        if ($newStatut !== $oldStatut && ($newStatut === "Cédé" || $newStatut === "Rebut")) {
            // Redirect to a specific page to enter cession/rebut details?
            // Or create a basic movement record here?
            // For now, just update.
        }

        // TODO: Update PlanAmortissement if relevant fields changed?

        return redirect()->route("immobilisations.index")
                         ->with("success", "Immobilisation mise à jour avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Immobilisation $immobilisation): RedirectResponse
    {
        if ($immobilisation->dossier_id != $this->getCurrentDossierId()) {
            abort(403, "Accès non autorisé.");
        }

        try {
            // Check for related data that might prevent deletion
            if ($immobilisation->mouvements()->exists() || 
                $immobilisation->planAmortissements()->exists() || 
                $immobilisation->contrats()->exists() || 
                $immobilisation->maintenances()->exists()) {
                return redirect()->route("immobilisations.index")
                               ->with("error", "Impossible de supprimer l'immobilisation car elle a un historique (mouvements, amortissements, contrats, maintenances). Changez son statut à \"Rebut\" ou \"Cédé\".");
            }

            // Delete photo if it exists
            if ($immobilisation->photo_path) {
                Storage::disk("public")->delete($immobilisation->photo_path);
            }

            // Delete document if it exists
            if ($immobilisation->document_path) {
                Storage::disk("public")->delete($immobilisation->document_path);
            }

            $immobilisation->delete();

            return redirect()->route("immobilisations.index")
                             ->with("success", "Immobilisation supprimée avec succès.");

        } catch (\Exception $e) {
            logger()->error("Error deleting immobilisation {$immobilisation->id}: ".$e->getMessage());
            return redirect()->route("immobilisations.index")
                             ->with("error", "Erreur lors de la suppression de l'immobilisation.");
        }
    }

    /**
     * Crée le plan d'amortissement initial pour une immobilisation.
     */
    protected function createInitialAmortizationPlan(Immobilisation $immobilisation)
    {
        // Logique de création du plan d'amortissement
        // À implémenter selon les règles métier
    }

    /**
     * Récupère les informations d'une famille pour l'affichage dynamique.
     */
    public function getFamilleInfo($id)
    {
        $famille = Famille::with(['compteImmobilisation', 'compteAmortissement', 'compteDotation'])
                          ->findOrFail($id);
        
        return response()->json([
            'comptecompta_immobilisation_id' => $famille->comptecompta_immobilisation_id,
            'comptecompta_immobilisation' => $famille->compteImmobilisation ? $famille->compteImmobilisation->numero . ' - ' . $famille->compteImmobilisation->libelle : null,
            'comptecompta_amortissement_id' => $famille->comptecompta_amortissement_id,
            'comptecompta_amortissement' => $famille->compteAmortissement ? $famille->compteAmortissement->numero . ' - ' . $famille->compteAmortissement->libelle : null,
            'comptecompta_dotation_id' => $famille->comptecompta_dotation_id,
            'comptecompta_dotation' => $famille->compteDotation ? $famille->compteDotation->numero . ' - ' . $famille->compteDotation->libelle : null,
            'duree_amortissement' => $famille->duree_amortissement_par_defaut,
            'methode_amortissement' => $famille->methode_amortissement_par_defaut,
        ]);
    }

    /**
     * Récupère les services pour un site et un dossier spécifiques.
     */
    public function getServicesBySite($dossierId, $siteId)
    {
        $services = Service::where('dossier_id', $dossierId)
                          ->where('site_id', $siteId)
                          ->orderBy('libelle')
                          ->get();
        
        return response()->json($services);
    }

    /**
     * Récupère tous les services pour un dossier spécifique.
     */
    public function getServicesByDossier($dossierId)
    {
        $services = Service::where('dossier_id', $dossierId)
                          ->orderBy('libelle')
                          ->get();
        
        return response()->json($services);
    }

    // --- Import / Export Methods --- //

    /**
     * Show the form for importing immobilisations.
     */
    public function showImportForm(): View
    {
        return view("immobilisations.import");
    }

    /**
     * Handle the import of immobilisations from Excel/CSV.
     */
    public function handleImport(Request $request): RedirectResponse
    {
        $request->validate([
            "import_file" => ["required", "file", "mimes:xlsx,xls,csv,txt"],
        ]);

        $dossierId = $this->getCurrentDossierId();
        $file = $request->file("import_file");

        try {
            // Pass dossierId to the import class constructor
            $import = new ImmobilisationsImport($dossierId);
            Excel::import($import, $file);

            $message = "Importation terminée avec succès.";
            if ($import->getImportedCount() > 0) {
                $message .= " " . $import->getImportedCount() . " immobilisation(s) importée(s).";
            }
            if ($import->getSkippedCount() > 0) {
                $message .= " " . $import->getSkippedCount() . " ligne(s) ignorée(s).";
            }
            if ($import->getErrorCount() > 0) {
                $message .= " " . $import->getErrorCount() . " erreur(s) rencontrée(s).";
            }

            return redirect()->route("immobilisations.index")->with("success", $message);

        } catch (\Exception $e) {
            logger()->error("Import error: " . $e->getMessage());
            return redirect()->route("immobilisations.import")->with("error", "Erreur lors de l'importation: " . $e->getMessage());
        }
    }

    /**
     * Export immobilisations to Excel/CSV.
     */
    public function export(Request $request)
    {
        $dossierId = $this->getCurrentDossierId();
        $format = $request->input("format", "xlsx");
        $filename = "immobilisations_" . date("Y-m-d") . "." . $format;

        // Apply filters from request if any
        $filters = $request->only([
            "filter_code_barre",
            "filter_designation",
            "filter_famille_id",
            "filter_site_id",
            "filter_service_id",
            "filter_statut",
            // Add other filters as needed
        ]);

        return Excel::download(new ImmobilisationsExport($dossierId, $filters), $filename);
    }
}
