<?php $__env->startSection("title", "Sessions d'Inventaire"); ?>

<?php $__env->startSection("content"); ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Sessions d'Inventaire</h1>
        <a href="<?php echo e(route('inventaires.create')); ?>" class="btn btn-primary">Nouvelle Session d'Inventaire</a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">Liste des sessions</div>
        <div class="card-body">
            <?php if($inventaires->isEmpty()): ?>
                <p>Aucune session d'inventaire n'a été créée pour ce dossier.</p>
            <?php else: ?>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Date Début</th>
                            <th>Date Fin</th>
                            <th>Statut</th>
                            <th>Description</th>
                            <th>Créé par</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $inventaires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventaire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($inventaire->reference); ?></td>
                                <td><?php echo e($inventaire->date_debut->format('d/m/Y')); ?></td>
                                <td><?php echo e($inventaire->date_fin ? $inventaire->date_fin->format('d/m/Y') : '-'); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($inventaire->statut == 'Terminé' ? 'success' : ($inventaire->statut == 'En cours' ? 'warning' : 'secondary')); ?>">
                                        <?php echo e($inventaire->statut); ?>

                                    </span>
                                </td>
                                <td><?php echo e(Str::limit($inventaire->description, 50)); ?></td>
                                <td><?php echo e($inventaire->user->name ?? 'N/A'); ?></td>
                                <td>
                                    <a href="<?php echo e(route('inventaires.show', $inventaire)); ?>" class="btn btn-sm btn-info" title="Voir"><i class="bi bi-eye"></i></a>
                                    <?php if($inventaire->statut != 'Terminé'): ?>
                                        <a href="<?php echo e(route('inventaires.edit', $inventaire)); ?>" class="btn btn-sm btn-warning" title="Modifier"><i class="bi bi-pencil"></i></a>
                                        
                                        
                                    <?php endif; ?>
                                     <a href="<?php echo e(route('inventaires.results', $inventaire)); ?>" class="btn btn-sm btn-secondary" title="Résultats"><i class="bi bi-clipboard-data"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                
                <div class="d-flex justify-content-center">
                     <?php echo e($inventaires->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/inventaires/index.blade.php ENDPATH**/ ?>