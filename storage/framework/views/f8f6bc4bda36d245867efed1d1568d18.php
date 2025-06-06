<?php $__env->startSection("title", "Sites Géographiques"); ?>

<?php $__env->startSection("content"); ?>
<div class="container">
    <h1>Sites Géographiques</h1>
    <p>Gérez les sites où se trouvent vos immobilisations.</p>

    <div class="mb-3">
        
        <a href="<?php echo e(route("parametres.sites.create")); ?>" class="btn btn-success">Nouveau Site</a>
    </div>

    <?php if(session("success")): ?>
        <div class="alert alert-success"><?php echo e(session("success")); ?></div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Libellé</th>
                <th>Adresse</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $sites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $site): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($site->id); ?></td>
                    <td><?php echo e($site->code); ?></td>
                    <td><?php echo e($site->libelle); ?></td>
                    <td><?php echo e($site->adresse); ?></td>
                    <td>
                        
                        <a href="<?php echo e(route("parametres.sites.edit", $site)); ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <form action="<?php echo e(route("parametres.sites.destroy", $site)); ?>" method="POST" style="display:inline;" onsubmit="return confirm("Êtes-vous sûr de vouloir supprimer ce site ?");">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field("DELETE"); ?>
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5">Aucun site trouvé pour ce dossier.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php echo e($sites->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/parametres/sites/index.blade.php ENDPATH**/ ?>