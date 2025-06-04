<?php $__env->startSection("title", "Immobilisations"); ?>

<?php $__env->startSection("content"); ?>
<div class="container-fluid">
    <h1>Gestion des Immobilisations</h1>

    <div class="card mb-3">
        <div class="card-header">Filtres</div>
        <div class="card-body">
            <form action="<?php echo e(route("immobilisations.index")); ?>" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="filter_code_barre" class="form-label">Code Barre</label>
                    <input type="text" class="form-control" id="filter_code_barre" name="filter_code_barre" value="<?php echo e(request("filter_code_barre")); ?>">
                </div>
                <div class="col-md-3">
                    <label for="filter_designation" class="form-label">Désignation</label>
                    <input type="text" class="form-control" id="filter_designation" name="filter_designation" value="<?php echo e(request("filter_designation")); ?>">
                </div>
                <div class="col-md-3">
                    <label for="filter_famille_id" class="form-label">Famille</label>
                    <select class="form-select" id="filter_famille_id" name="filter_famille_id">
                        <option value="">Toutes</option>
                        
                        <?php $__currentLoopData = $familles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $famille): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($famille->id); ?>" <?php echo e(request("filter_famille_id") == $famille->id ? "selected" : ""); ?>><?php echo e($famille->libelle); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                 <div class="col-md-3">
                    <label for="filter_site_id" class="form-label">Site</label>
                    <select class="form-select" id="filter_site_id" name="filter_site_id">
                        <option value="">Tous</option>
                         
                        <?php $__currentLoopData = $sites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $site): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($site->id); ?>" <?php echo e(request("filter_site_id") == $site->id ? "selected" : ""); ?>><?php echo e($site->libelle); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                 <div class="col-md-3">
                    <label for="filter_service_id" class="form-label">Service</label>
                    <select class="form-select" id="filter_service_id" name="filter_service_id">
                        <option value="">Tous</option>
                         
                         <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($service->id); ?>" <?php echo e(request("filter_service_id") == $service->id ? "selected" : ""); ?>><?php echo e($service->libelle); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <a href="<?php echo e(route("immobilisations.index")); ?>" class="btn btn-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mb-3 d-flex justify-content-between">
        <a href="<?php echo e(route("immobilisations.create")); ?>" class="btn btn-success">Nouvelle Immobilisation</a>
    </div>

    <?php if(session("success")): ?>
        <div class="alert alert-success"><?php echo e(session("success")); ?></div>
    <?php endif; ?>
    <?php if(session("error")): ?>
        <div class="alert alert-danger"><?php echo e(session("error")); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Code Barre</th>
                    <th>Désignation</th>
                    <th>Famille</th>
                    <th>Site</th>
                    <th>Service</th>
                    <th>Date Acq.</th>
                    <th>Valeur Acq.</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $immobilisations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $immo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($immo->id); ?></td>
                        <td><?php echo e($immo->code_barre); ?></td>
                        <td><?php echo e($immo->designation); ?></td>
                        <td><?php echo e($immo->famille->libelle ?? "N/A"); ?></td>
                        <td><?php echo e($immo->site->libelle ?? "N/A"); ?></td>
                        <td><?php echo e($immo->service->libelle ?? "N/A"); ?></td>
                        <td><?php echo e($immo->date_acquisition ? $immo->date_acquisition->format("d/m/Y") : "N/A"); ?></td>
                        <td class="text-end"><?php echo e(number_format($immo->valeur_acquisition, 2, ",", " ")); ?></td>
                        <td><span class="badge bg-<?php echo e($immo->statut == "En service" ? "success" : ($immo->statut == "Cédé" || $immo->statut == "Rebut" ? "danger" : "secondary")); ?>"><?php echo e($immo->statut); ?></span></td>
                        <td>
                            <a href="<?php echo e(route("immobilisations.show", $immo)); ?>" class="btn btn-info btn-sm" title="Voir"><i class="bi bi-eye"></i></a>
                            <a href="<?php echo e(route("immobilisations.edit", $immo)); ?>" class="btn btn-warning btn-sm" title="Modifier"><i class="bi bi-pencil"></i></a>
                            <form action="<?php echo e(route("immobilisations.destroy", $immo)); ?>" method="POST" style="display:inline;" onsubmit="return confirm("Êtes-vous sûr de vouloir supprimer cette immobilisation ? Attention, cela peut affecter les calculs d'amortissement.");">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field("DELETE"); ?>
                                <button type="submit" class="btn btn-danger btn-sm" title="Supprimer"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" class="text-center">Aucune immobilisation trouvée pour ce dossier ou avec ces filtres.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php echo e($immobilisations->appends(request()->query())->links()); ?> 

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush("styles"); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<?php $__env->stopPush(); ?>

<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/immobilisations/index.blade.php ENDPATH**/ ?>