<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sélection du dossier</h5>
                    <?php if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin()): ?>
                    <a href="<?php echo e(route('dossiers.create')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nouveau dossier
                    </a>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Nom</th>
                                    <th>Client</th>
                                    <th>Exercice</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $dossiers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dossier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($dossier->code); ?></td>
                                    <td><?php echo e($dossier->nom); ?></td>
                                    <td><?php echo e($dossier->client->nom ?? 'N/A'); ?></td>
                                    <td>
                                        <?php if($dossier->exercice_debut && $dossier->exercice_fin): ?>
                                            <?php echo e($dossier->exercice_debut->format('d/m/Y')); ?> - <?php echo e($dossier->exercice_fin->format('d/m/Y')); ?>

                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($dossier->est_cloture): ?>
                                            <span class="badge bg-danger">Clôturé</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Actif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('dossiers.select', $dossier->id)); ?>" class="btn btn-success btn-sm" title="Sélectionner">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <a href="<?php echo e(route('dossiers.show', $dossier->id)); ?>" class="btn btn-info btn-sm" title="Détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if(Auth::user()->isSuperAdmin() || (Auth::user()->isAdmin() && Auth::user()->client_id == $dossier->client_id)): ?>
                                            <a href="<?php echo e(route('dossiers.edit', $dossier->id)); ?>" class="btn btn-warning btn-sm" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php endif; ?>
                                            <?php if(Auth::user()->isSuperAdmin()): ?>
                                            <form action="<?php echo e(route('dossiers.destroy', $dossier->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce dossier ?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Aucun dossier trouvé</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/dossiers/index.blade.php ENDPATH**/ ?>