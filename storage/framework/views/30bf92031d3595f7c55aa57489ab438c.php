<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Liste des comptes comptables</h5>
                    <a href="<?php echo e(route('parametres.comptescompta.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nouveau compte
                    </a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success mx-4 mt-3">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger mx-4 mt-3">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Numéro</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Libellé</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $comptescompta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $compte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex px-3 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm"><?php echo e($compte->numero); ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm font-weight-bold mb-0"><?php echo e($compte->libelle); ?></p>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-<?php echo e($compte->type == 'actif' ? 'success' : ($compte->type == 'passif' ? 'info' : ($compte->type == 'charge' ? 'warning' : 'primary'))); ?>">
                                                <?php echo e(ucfirst($compte->type)); ?>

                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex">
                                                <a href="<?php echo e(route('parametres.comptescompta.show', $compte->id)); ?>" class="btn btn-sm btn-info mx-1" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('parametres.comptescompta.edit', $compte->id)); ?>" class="btn btn-sm btn-warning mx-1" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('parametres.comptescompta.destroy', $compte->id)); ?>" method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-danger mx-1" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce compte comptable ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4">Aucun compte comptable trouvé</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <?php echo e($comptescompta->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/parametres/comptescompta/index.blade.php ENDPATH**/ ?>