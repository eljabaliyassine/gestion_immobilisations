<?php $__env->startSection("title", "Fournisseurs"); ?>

<?php $__env->startSection("content"); ?>
<div class="container">
    <h1>Fournisseurs</h1>
    <p>Gérez les fournisseurs de vos immobilisations.</p>

    <div class="mb-3">
        
        <a href="<?php echo e(route("parametres.fournisseurs.create")); ?>" class="btn btn-success">Nouveau Fournisseur</a>
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
            <?php $__empty_1 = true; $__currentLoopData = $fournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fournisseur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($fournisseur->id); ?></td>
                    <td><?php echo e($fournisseur->code); ?></td>
                    <td><?php echo e($fournisseur->nom); ?></td>
                    <td><?php echo e($fournisseur->adresse); ?></td>
                    <td><?php echo e($fournisseur->telephone); ?></td>
                    <td><?php echo e($fournisseur->email); ?></td>
                    <td>
                        
                        <a href="<?php echo e(route("parametres.fournisseurs.edit", $fournisseur)); ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <form action="<?php echo e(route("parametres.fournisseurs.destroy", $fournisseur)); ?>" method="POST" style="display:inline;" onsubmit="return confirm("Êtes-vous sûr de vouloir supprimer ce fournisseur ?");">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field("DELETE"); ?>
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7">Aucun fournisseur trouvé pour ce dossier.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php echo e($fournisseurs->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/parametres/fournisseurs/index.blade.php ENDPATH**/ ?>