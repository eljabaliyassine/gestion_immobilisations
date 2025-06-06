<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Modifier le compte comptable</h5>
                    <div>
                        <a href="<?php echo e(route('parametres.comptescompta.show', $comptecompta->id)); ?>" class="btn btn-info me-2">
                            <i class="fas fa-eye me-2"></i>Voir
                        </a>
                        <a href="<?php echo e(route('parametres.comptescompta.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('parametres.comptescompta.update', $comptecompta->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="numero" class="form-control-label">Numéro <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="numero" name="numero" value="<?php echo e(old('numero', $comptecompta->numero)); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="libelle" class="form-control-label">Libellé <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="libelle" name="libelle" value="<?php echo e(old('libelle', $comptecompta->libelle)); ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="form-control-label">Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="actif" <?php echo e(old('type', $comptecompta->type) == 'actif' ? 'selected' : ''); ?>>Actif</option>
                                        <option value="passif" <?php echo e(old('type', $comptecompta->type) == 'passif' ? 'selected' : ''); ?>>Passif</option>
                                        <option value="charge" <?php echo e(old('type', $comptecompta->type) == 'charge' ? 'selected' : ''); ?>>Charge</option>
                                        <option value="produit" <?php echo e(old('type', $comptecompta->type) == 'produit' ? 'selected' : ''); ?>>Produit</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categorie" class="form-control-label">Catégorie</label>
                                    <input type="text" class="form-control" id="categorie" name="categorie" value="<?php echo e(old('categorie', $comptecompta->categorie)); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description" class="form-control-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo e(old('description', $comptecompta->description)); ?></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/parametres/comptescompta/edit.blade.php ENDPATH**/ ?>