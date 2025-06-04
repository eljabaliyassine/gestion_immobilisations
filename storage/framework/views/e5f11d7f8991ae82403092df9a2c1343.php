<?php $__env->startSection("title", "Exports Fiscaux et Comptables"); ?>

<?php $__env->startSection("content"); ?>
<div class="container">
    <h1>Exports Fiscaux et Comptables</h1>
    <p>Sélectionnez le type d'export que vous souhaitez générer au format CSV pour le dossier actif : 
        <strong>
            <?php
                $dossierId = session("current_dossier_id") ?? session("dossier_id");
                $dossierName = "N/A";
                
                if ($dossierId) {
                    // Récupérer le nom du dossier depuis la base de données
                    $dossier = \App\Models\Dossier::find($dossierId);
                    if ($dossier) {
                        $dossierName = $dossier->nom;
                    }
                }
            ?>
            <?php echo e($dossierName); ?>

        </strong>
    </p>
    <p>Générer plan d'amortissement avant d'exporter les données : <a href="<?php echo e(route("amortissements.plan")); ?>" class="btn btn-primary btn-sm">Générer amortissements</a></p>
    <div class="row">
        
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">Exports Fiscaux (Tableaux)</div>
                <div class="card-body">
                    <p>Générez les tableaux fiscaux requis au format CSV.</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Tableau 4 : Immobilisations
                            <a href="<?php echo e(route("exports.tableau4")); ?>" class="btn btn-primary btn-sm">Générer CSV</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Tableau 8 : Amortissements
                            <a href="<?php echo e(route("exports.tableau8")); ?>" class="btn btn-primary btn-sm">Générer CSV</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Tableau 16 : Dotations aux amortissements
                            <a href="<?php echo e(route("exports.tableau16")); ?>" class="btn btn-primary btn-sm">Générer CSV</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">Exports Comptables</div>
                <div class="card-body">
                    <p>Générez les écritures comptables au format CSV.</p>
                     <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Écritures Comptables (Dotations aux Amortissements)
                            
                            <a href="<?php echo e(route("exports.ecritures_comptables")); ?>" class="btn btn-primary btn-sm">Générer CSV</a>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </div>

        
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">Export Données Brutes</div>
                <div class="card-body">
                    <p>Exportez les tables de données brutes au format CSV.</p>
                     <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Fichier des Immobilisations
                            <a href="<?php echo e(route('exports.immobilisations.completes')); ?>" class="btn btn-primary btn-sm">Exporter toutes les immobilisations</a>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/exports/index.blade.php ENDPATH**/ ?>