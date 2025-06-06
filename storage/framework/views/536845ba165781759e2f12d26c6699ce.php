<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><?php echo e(__('Création d\'une famille')); ?></span>
                    <a href="<?php echo e(route('parametres.familles.index')); ?>" class="btn btn-sm btn-secondary">Retour à la liste</a>
                </div>

                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('parametres.familles.store')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="row mb-3">
                            <label for="code" class="col-md-4 col-form-label text-md-end"><?php echo e(__('Code')); ?> *</label>
                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="code" value="<?php echo e(old('code')); ?>" required autocomplete="code" autofocus>
                                <?php $__errorArgs = ['code'];
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
                            <label for="libelle" class="col-md-4 col-form-label text-md-end"><?php echo e(__('Libellé')); ?> *</label>
                            <div class="col-md-6">
                                <input id="libelle" type="text" class="form-control <?php $__errorArgs = ['libelle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="libelle" value="<?php echo e(old('libelle')); ?>" required autocomplete="libelle">
                                <?php $__errorArgs = ['libelle'];
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
                            <label for="comptecompta_immobilisation_id" class="col-md-4 col-form-label text-md-end"><?php echo e(__('Compte Immobilisation')); ?> *</label>
                            <div class="col-md-6">
                                <select id="comptecompta_immobilisation_id" class="form-control <?php $__errorArgs = ['comptecompta_immobilisation_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="comptecompta_immobilisation_id" required>
                                    <option value="">Sélectionnez un compte</option>
                                    <?php $__currentLoopData = $comptesCompta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $compte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($compte->id); ?>" <?php echo e(old('comptecompta_immobilisation_id') == $compte->id ? 'selected' : ''); ?>>
                                            <?php echo e($compte->numero); ?> - <?php echo e($compte->libelle); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['comptecompta_immobilisation_id'];
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
                            <label for="comptecompta_amortissement_id" class="col-md-4 col-form-label text-md-end"><?php echo e(__('Compte Amortissement')); ?> *</label>
                            <div class="col-md-6">
                                <select id="comptecompta_amortissement_id" class="form-control <?php $__errorArgs = ['comptecompta_amortissement_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="comptecompta_amortissement_id" required>
                                    <option value="">Sélectionnez un compte</option>
                                    <?php $__currentLoopData = $comptesCompta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $compte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($compte->id); ?>" <?php echo e(old('comptecompta_amortissement_id') == $compte->id ? 'selected' : ''); ?>>
                                            <?php echo e($compte->numero); ?> - <?php echo e($compte->libelle); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['comptecompta_amortissement_id'];
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
                            <label for="comptecompta_dotation_id" class="col-md-4 col-form-label text-md-end"><?php echo e(__('Compte Dotation')); ?> *</label>
                            <div class="col-md-6">
                                <select id="comptecompta_dotation_id" class="form-control <?php $__errorArgs = ['comptecompta_dotation_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="comptecompta_dotation_id" required>
                                    <option value="">Sélectionnez un compte</option>
                                    <?php $__currentLoopData = $comptesCompta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $compte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($compte->id); ?>" <?php echo e(old('comptecompta_dotation_id') == $compte->id ? 'selected' : ''); ?>>
                                            <?php echo e($compte->numero); ?> - <?php echo e($compte->libelle); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['comptecompta_dotation_id'];
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
                            <label for="duree_amortissement_par_defaut" class="col-md-4 col-form-label text-md-end"><?php echo e(__('Durée d\'amortissement par défaut')); ?></label>
                            <div class="col-md-6">
                                <input id="duree_amortissement_par_defaut" type="number" min="1" max="50" class="form-control <?php $__errorArgs = ['duree_amortissement_par_defaut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="duree_amortissement_par_defaut" value="<?php echo e(old('duree_amortissement_par_defaut')); ?>" autocomplete="duree_amortissement_par_defaut">
                                <?php $__errorArgs = ['duree_amortissement_par_defaut'];
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
                            <label for="methode_amortissement_par_defaut" class="col-md-4 col-form-label text-md-end"><?php echo e(__('Méthode d\'amortissement par défaut')); ?></label>
                            <div class="col-md-6">
                                <select id="methode_amortissement_par_defaut" class="form-control <?php $__errorArgs = ['methode_amortissement_par_defaut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="methode_amortissement_par_defaut">
                                    <option value="">Sélectionnez une méthode</option>
                                    <option value="lineaire" <?php echo e(old('methode_amortissement_par_defaut') == 'lineaire' ? 'selected' : ''); ?>>Linéaire</option>
                                    <option value="degressif" <?php echo e(old('methode_amortissement_par_defaut') == 'degressif' ? 'selected' : ''); ?>>Dégressif</option>
                                </select>
                                <?php $__errorArgs = ['methode_amortissement_par_defaut'];
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

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo e(__('Enregistrer')); ?>

                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/parametres/familles/create.blade.php ENDPATH**/ ?>