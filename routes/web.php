<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserActionsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DossierController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SocieteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Parametres\FamilleController;
use App\Http\Controllers\Parametres\SiteController;
use App\Http\Controllers\Parametres\ServiceController;
use App\Http\Controllers\Parametres\FournisseurController;
use App\Http\Controllers\Parametres\PrestataireController;
use App\Http\Controllers\Parametres\CompteComptaController;
use App\Http\Controllers\ImmobilisationController;
use App\Http\Controllers\AmortissementController;
use App\Http\Controllers\InventaireController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\MouvementController;
use App\Http\Controllers\MaintenanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/", function () {
    if (Auth::check()) {
        return redirect()->route("home");
    } else {
        return redirect()->route("login");
    }
});

Auth::routes(['register' => false]); // Désactiver l'enregistrement public

Route::middleware(["auth"])->group(function () {
    // Dossier Selection
    Route::get("/dossiers/select", [DossierController::class, "select"])->name("dossiers.select");
    Route::post("/dossiers/select", [DossierController::class, "storeSelection"])->name("dossiers.storeSelection");

    // Routes pour la gestion des dossiers (accessibles sans dossier sélectionné)
    Route::resource("dossiers", DossierController::class);

    // Routes pour la gestion des clients (accessibles sans dossier sélectionné)
    Route::resource("clients", ClientController::class);

    // Routes pour la gestion des sociétés (accessibles sans dossier sélectionné, SuperAdmin uniquement)
    Route::middleware(["can:manage-societes"])->resource("societes", SocieteController::class);

    // Routes pour la gestion des utilisateurs (accessibles sans dossier sélectionné)
    Route::resource("users", UserController::class);

    // Route personnalisée pour mettre à jour les dossiers d'un utilisateur
    Route::post("/users/{user}/update-dossiers", [UserController::class, "updateDossiers"])->name("users.update_dossiers");


    // Toutes les routes nécessitant un dossier sélectionné
    Route::middleware(["dossier.selected"])->group(function () {
        // Home route
        Route::get("/home", [HomeController::class, "index"])->name("home");

        // Immobilisations Management
        Route::prefix("immobilisations")->name("immobilisations.")->group(function () {
            Route::get("/import", [ImmobilisationController::class, "showImportForm"])->name("import.form");
            Route::post("/import", [ImmobilisationController::class, "handleImport"])->name("import.handle");
            // Exceptional Depreciation Route
            Route::get("/{immobilisation}/amortissement-exceptionnel/create", [AmortissementController::class, "createExceptionnel"])->name("amortissement.exceptionnel.create");
            Route::post("/{immobilisation}/amortissement-exceptionnel", [AmortissementController::class, "storeExceptionnel"])->name("amortissement.exceptionnel.store");
        });
        // Routes AJAX pour les immobilisations
        Route::post('/immobilisations/ajax/services-by-site', [ImmobilisationController::class, 'ajaxGetServicesBySite'])
            ->name('immobilisations.ajax.services-by-site');

        Route::post('/immobilisations/ajax/famille-info', [ImmobilisationController::class, 'ajaxGetFamilleInfo'])
            ->name('immobilisations.ajax.famille-info');

        Route::resource("immobilisations", ImmobilisationController::class);

        // Routes pour les inventaires
Route::prefix('inventaires')->name('inventaires.')->group(function () {
    Route::get('/', [InventaireController::class, 'index'])->name('index');
    Route::get('/create', [InventaireController::class, 'create'])->name('create');
    Route::post('/', [InventaireController::class, 'store'])->name('store');
    Route::get('/{inventaire}', [InventaireController::class, 'show'])->name('show');
    Route::get('/{inventaire}/edit', [InventaireController::class, 'edit'])->name('edit');
    Route::put('/{inventaire}', [InventaireController::class, 'update'])->name('update');
    Route::delete('/{inventaire}', [InventaireController::class, 'destroy'])->name('destroy');

    // Routes pour les comptages
    Route::get('/{inventaire}/add-count', [InventaireController::class, 'showAddCountForm'])->name('add_count.form');
    Route::post('/{inventaire}/add-count', [InventaireController::class, 'storeCount'])->name('store_count');
    Route::post('/{inventaire}/update-count', [InventaireController::class, 'updateCount'])->name('update_count');

    // Routes pour l'import
    Route::get('/{inventaire}/import-counts', [InventaireController::class, 'showImportCountsForm'])->name('import_counts.form');
    Route::post('/{inventaire}/import-counts', [InventaireController::class, 'handleImportCounts'])->name('import_counts.handle');

    // Routes pour les résultats
    Route::get('/{inventaire}/results', [InventaireController::class, 'showResults'])->name('results');
    Route::post('/{inventaire}/process', [InventaireController::class, 'processCounts'])->name('process');

    // Routes pour l'export
    Route::get('/{inventaire}/export/immobilisations', [InventaireController::class, 'exportImmobilisationsCSV'])->name('export.immobilisations');
    Route::get('/{inventaire}/export/resultats', [InventaireController::class, 'exportResultatsCSV'])->name('export.resultats');
});
        // Parameters Management
        Route::prefix("parametres")->name("parametres.")->group(function () {
            Route::resource("familles", FamilleController::class);
            Route::resource("sites", SiteController::class);
            Route::resource("services", ServiceController::class);
            Route::resource("fournisseurs", FournisseurController::class);
            Route::resource("prestataires", PrestataireController::class);
            Route::resource("comptescompta", CompteComptaController::class);

            // Routes d'export
            Route::get('comptescompta/export/{format}', [CompteComptaController::class, 'export'])
            ->name('comptescompta.export')
            ->where('format', 'excel|pdf|csv');
        });

        // Exports Management (Fiscal & Comptable)
        Route::prefix("exports")->name("exports.")->group(function () {
            Route::get("/", [ExportController::class, "index"])->name("index"); // Page to choose export type
            Route::get("/tableau4", [ExportController::class, "exportTableau4"])->name("tableau4"); // Immobilisations (Fiscal)
            Route::get("/tableau8", [ExportController::class, "exporttableau8"])->name("tableau8"); // Amortissements (Fiscal)
            Route::get("/tableau16", [App\Http\Controllers\ExportT16Controller::class, "exportTableau16"])->name("tableau16");
            Route::get("/ecritures-comptables", [ExportController::class, "exportEcrituresComptables"])->name("ecritures_comptables");
            Route::get("/immobilisations-completes", [App\Http\Controllers\ExportimmosController::class, "exportImmobilisationsCompletes"])->name("immobilisations.completes");
        });

        // Routes pour les contrats
Route::middleware(['auth', 'dossier.selected'])->group(function () {
    // Routes CRUD de base
    Route::get('/contrats', [ContratController::class, 'index'])->name('contrats.index');
    Route::get('/contrats/create', [ContratController::class, 'create'])->name('contrats.create');
    Route::post('/contrats', [ContratController::class, 'store'])->name('contrats.store');
    Route::get('/contrats/{contrat}', [ContratController::class, 'show'])->name('contrats.show');
    Route::get('/contrats/{contrat}/edit', [ContratController::class, 'edit'])->name('contrats.edit');
    Route::put('/contrats/{contrat}', [ContratController::class, 'update'])->name('contrats.update');
    Route::delete('/contrats/{contrat}', [ContratController::class, 'destroy'])->name('contrats.destroy');

    // Export CSV
    Route::get('/contrat/export-csv', [App\Http\Controllers\ContratController::class, 'exportCsv'])->name('contrat.exportCsv.csv');

    // Gestion des immobilisations liées
    Route::get('/contrats/{contrat}/immobilisations', [ContratController::class, 'immobilisations'])->name('contrats.immobilisations');
    Route::post('/contrats/{contrat}/immobilisations', [ContratController::class, 'lierImmobilisations'])->name('contrats.lierImmobilisations');
    Route::put('/contrats/{contrat}/immobilisations/{immobilisation}/delier', [ContratController::class, 'delierImmobilisation'])->name('contrats.delierImmobilisation');
    Route::delete('/contrats/{contrat}/immobilisations/{immobilisation}', [ContratController::class, 'detachImmobilisation'])->name('contrats.detachImmobilisation');

    // Gestion des échéances de crédit-bail
    Route::get('/contrats/{contrat}/echeances', [ContratController::class, 'echeances'])->name('contrats.echeances');
    Route::post('/contrats/{contrat}/echeances/regenerer', [ContratController::class, 'regenererEcheances'])->name('contrats.regenererEcheances');
    Route::put('/contrats/{contrat}/echeances/{echeance}/payer', [ContratController::class, 'payerEcheance'])->name('contrats.payerEcheance');
    Route::put('/contrats/{contrat}/echeances/{echeance}/annuler-paiement', [ContratController::class, 'annulerPaiementEcheance'])->name('contrats.annulerPaiementEcheance');

    // Export des échéances
    Route::get('/contrats/{contrat}/echeances/export-csv', [ContratController::class, 'exportEcheancesCsv'])->name('contrats.exportEcheancesCsv');
    Route::get('/contrats/{contrat}/echeances/export-excel', [ContratController::class, 'exportEcheancesExcel'])->name('contrats.exportEcheancesExcel');
});



        // Routes for Amortissements
        Route::resource("amortissements", AmortissementController::class);

        // Routes for Plan d'Amortissement
        Route::get("/plan-amortissement", [AmortissementController::class, "planAmortissement"])->name("amortissements.plan");
        Route::post("/plan-amortissement/generer", [AmortissementController::class, "genererPlanAmortissement"])->name("amortissements.generer");
        Route::get("/plan-amortissement/export", [AmortissementController::class, "exportPlanAmortissement"])->name("amortissements.export");
        Route::get("/plan-amortissement/export-csv", [App\Http\Controllers\ExportCSVController::class, "exportPlanAmortissementCSV"])->name("amortissements.export.csv");

        // Routes for Mouvements
        Route::resource("mouvements", MouvementController::class);
        // Routes for Maintenances
        Route::resource("maintenances", MaintenanceController::class);
    });
    Route::get("user/profile", [ProfileController::class, "edit"])->name("user.profile.edit");
    Route::patch("user/profile", [ProfileController::class, "update"])->name("user.profile.update");
    Route::put("user/profile/password", [ProfileController::class, "updatePassword"])->name("password.update");
    Route::delete("user/profile", [ProfileController::class, "destroy"])->name("user.profile.destroy");
// Nouvelles routes pour les actions rapides
Route::get("user/download-data", [UserActionsController::class, "downloadUserData"])->name("user.download.data");
Route::get("user/login-history", [UserActionsController::class, "loginHistory"])->name("user.login.history");
Route::get("user/help-center", [UserActionsController::class, "helpCenter"])->name("user.help.center");
});

// Route pour la vérification email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(["auth", "dossier.selected"])->prefix('api')->name('api.')->group(function () {
    Route::get("/dossiers/{dossierId}/sites/{siteId}/services", function ($dossierId, $siteId) {
        // Security check: ensure requested dossier matches session dossier
        if ($dossierId != session("dossier_id")) {
            return response()->json(["error" => "Unauthorized"], 403);
        }

        $services = App\Models\Service::where("dossier_id", $dossierId)
                                      ->where("site_id", $siteId)
                                      ->orderBy("libelle")
                                      ->get(["id", "libelle"]);

        return response()->json($services);
    })->name("site.services");
});
