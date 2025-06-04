<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-1"><?php echo e(__('Update Password')); ?></h4>
        <p class="card-text text-muted">
            <?php echo e(__('Ensure your account is using a long, random password to stay secure.')); ?>

        </p>
    </div>

    <div class="card-body">
        <form method="post" action="<?php echo e(route('password.update')); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('put'); ?>

            <!-- Mot de passe actuel -->
            <div class="mb-3">
                <label for="update_password_current_password" class="form-label">
                    <?php echo e(__('Current Password')); ?>

                </label>
                <input type="password"
                       class="form-control <?php $__errorArgs = ['current_password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       id="update_password_current_password"
                       name="current_password"
                       autocomplete="current-password">

                <?php if($errors->updatePassword->get('current_password')): ?>
                    <div class="invalid-feedback">
                        <?php $__currentLoopData = $errors->updatePassword->get('current_password'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div><?php echo e($error); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Nouveau mot de passe -->
            <div class="mb-3">
                <label for="update_password_password" class="form-label">
                    <?php echo e(__('New Password')); ?>

                </label>
                <input type="password"
                       class="form-control <?php $__errorArgs = ['password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       id="update_password_password"
                       name="password"
                       autocomplete="new-password">

                <?php if($errors->updatePassword->get('password')): ?>
                    <div class="invalid-feedback">
                        <?php $__currentLoopData = $errors->updatePassword->get('password'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div><?php echo e($error); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Confirmation du mot de passe -->
            <div class="mb-3">
                <label for="update_password_password_confirmation" class="form-label">
                    <?php echo e(__('Confirm Password')); ?>

                </label>
                <input type="password"
                       class="form-control <?php $__errorArgs = ['password_confirmation', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       id="update_password_password_confirmation"
                       name="password_confirmation"
                       autocomplete="new-password">

                <?php if($errors->updatePassword->get('password_confirmation')): ?>
                    <div class="invalid-feedback">
                        <?php $__currentLoopData = $errors->updatePassword->get('password_confirmation'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div><?php echo e($error); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Boutons d'action -->
            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-dark">
                    <?php echo e(__('Save')); ?>

                </button>

                <?php if(session('status') === 'password-updated'): ?>
                    <div class="text-success fade-message">
                        <small><?php echo e(__('Saved.')); ?></small>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<style>
.fade-message {
    animation: fadeOut 2s ease-in-out 2s forwards;
}

@keyframes fadeOut {
    0% { opacity: 1; }
    100% { opacity: 0; }
}
</style>
<?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/user/profile/partials/update-password-form.blade.php ENDPATH**/ ?>