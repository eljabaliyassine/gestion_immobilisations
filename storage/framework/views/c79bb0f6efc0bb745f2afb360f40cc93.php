<?php $__env->startSection("title", "Nouvelle Session d'Inventaire"); ?>

<?php $__env->startSection("content"); ?>
<div class="container">
    <h1>Nouvelle Session d'Inventaire</h1>

    <div class="card">
        <div class="card-header">Informations sur la session</div>
        <div class="card-body">
            <form action="<?php echo e(route("inventaires.store")); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="row mb-3">
                    <label for="reference" class="col-md-4 col-form-label text-md-end">Référence</label>
                    <div class="col-md-6">
                        <input id="reference" type="text" class="form-control <?php $__errorArgs = ["reference"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="reference" value="<?php echo e(old("reference", "INV-" . date("Ymd-His"))); ?>" required autofocus>
                        <?php $__errorArgs = ["reference"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="date_debut" class="col-md-4 col-form-label text-md-end">Date de Début</label>
                    <div class="col-md-6">
                        <input id="date_debut" type="date" class="form-control <?php $__errorArgs = ["date_debut"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="date_debut" value="<?php echo e(old("date_debut", date("Y-m-d"))); ?>" required>
                        <?php $__errorArgs = ["date_debut"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="description" class="col-md-4 col-form-label text-md-end">Description</label>
                    <div class="col-md-6">
                        <textarea id="description" class="form-control <?php $__errorArgs = ["description"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="description" rows="3"><?php echo e(old("description")); ?></textarea>
                        <?php $__errorArgs = ["description"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                 <div class="row mb-3">
                        <label for="statut" class="col-md-4 col-form-label text-md-end">Statut *</label>
                        <div class="col-md-6">
                            <select id="statut" name="statut" class="form-control <?php $__errorArgs = ['statut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="Planifié" <?php echo e(old('statut') == 'Planifié' ? 'selected' : ''); ?>>Planifié</option>
                                <option value="En cours" <?php echo e(old('statut') == 'En cours' ? 'selected' : ''); ?>>En cours</option>
                                <option value="Terminé" <?php echo e(old('statut') == 'Terminé' ? 'selected' : ''); ?>>Terminé</option>
                                <option value="Annulé" <?php echo e(old('statut') == 'Annulé' ? 'selected' : ''); ?>>Annulé</option>
                            </select>
                            <?php $__errorArgs = ['statut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                </div>

                

                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Créer la Session
                        </button>
                        <a href="<?php echo e(route("inventaires.index")); ?>" class="btn btn-secondary">Annuler</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/inventaires/create.blade.php ENDPATH**/ ?>