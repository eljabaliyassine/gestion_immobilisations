<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><?php echo e(__('Liste des familles')); ?></span>
                    <a href="<?php echo e(route('parametres.familles.create')); ?>" class="btn btn-sm btn-primary">Ajouter une famille</a>
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
                                    <th>Libellé</th>
                                    <th>Compte Immobilisation</th>
                                    <th>Compte Amortissement</th>
                                    <th>Compte Dotation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $familles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $famille): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($famille->code); ?></td>
                                        <td><?php echo e($famille->libelle); ?></td>
                                        <td>
                                            <?php if($famille->compteImmobilisation): ?>
                                                <?php echo e($famille->compteImmobilisation->numero); ?> - <?php echo e($famille->compteImmobilisation->libelle); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($famille->compteAmortissement): ?>
                                                <?php echo e($famille->compteAmortissement->numero); ?> - <?php echo e($famille->compteAmortissement->libelle); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($famille->compteDotation): ?>
                                                <?php echo e($famille->compteDotation->numero); ?> - <?php echo e($famille->compteDotation->libelle); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('parametres.familles.edit', $famille->id)); ?>" class="btn btn-sm btn-info">Modifier</a>
                                                <form action="<?php echo e(route('parametres.familles.destroy', $famille->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette famille ?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Aucune famille trouvée</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <?php echo e($familles->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/parametres/familles/index.blade.php ENDPATH**/ ?>