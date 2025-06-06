<?php $__env->startSection("title", "Services"); ?>

<?php $__env->startSection("content"); ?>
<div class="container">
    <h1>Services</h1>
    <p>Gérez les services auxquels vos immobilisations sont affectées.</p>

    <div class="mb-3">
        
        <a href="<?php echo e(route("parametres.services.create")); ?>" class="btn btn-success">Nouveau Service</a>
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
                <th>Site Associé</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($service->id); ?></td>
                    <td><?php echo e($service->code); ?></td>
                    <td><?php echo e($service->libelle); ?></td>
                    <td><?php echo e($service->site->libelle ?? "N/A"); ?></td> 
                    <td>
                        
                        <a href="<?php echo e(route("parametres.services.edit", $service)); ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <form action="<?php echo e(route("parametres.services.destroy", $service)); ?>" method="POST" style="display:inline;" onsubmit="return confirm("Êtes-vous sûr de vouloir supprimer ce service ?");">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field("DELETE"); ?>
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5">Aucun service trouvé pour ce dossier.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php echo e($services->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/parametres/services/index.blade.php ENDPATH**/ ?>