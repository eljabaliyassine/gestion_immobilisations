<?php $__env->startSection("title", "Prestataires"); ?>

<?php $__env->startSection("content"); ?>
<div class="container">
    <h1>Prestataires</h1>
    <p>Gérez les prestataires de services (maintenance, etc.).</p>

    <div class="mb-3">
        
        <a href="<?php echo e(route("parametres.prestataires.create")); ?>" class="btn btn-success">Nouveau Prestataire</a>
    </div>

    <?php if(session("success")): ?>
        <div class="alert alert-success"><?php echo e(session("success")); ?></div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Nom</th>
                <th>Adresse</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $prestataires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prestataire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($prestataire->id); ?></td>
                    <td><?php echo e($prestataire->code); ?></td>
                    <td><?php echo e($prestataire->nom); ?></td>
                    <td><?php echo e($prestataire->adresse); ?></td>
                    <td><?php echo e($prestataire->telephone); ?></td>
                    <td><?php echo e($prestataire->email); ?></td>
                    <td>
                        
                        <a href="<?php echo e(route("parametres.prestataires.edit", $prestataire)); ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <form action="<?php echo e(route("parametres.prestataires.destroy", $prestataire)); ?>" method="POST" style="display:inline;" onsubmit="return confirm("Êtes-vous sûr de vouloir supprimer ce prestataire ?");">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field("DELETE"); ?>
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7">Aucun prestataire trouvé pour ce dossier.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php echo e($prestataires->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/parametres/prestataires/index.blade.php ENDPATH**/ ?>