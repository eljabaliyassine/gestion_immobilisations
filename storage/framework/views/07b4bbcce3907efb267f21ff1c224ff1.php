<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-1 text-danger"><?php echo e(__('Delete Account')); ?></h4>
        <p class="card-text text-muted">
            <?php echo e(__('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.')); ?>

        </p>
    </div>

    <div class="card-body">
        <button type="button"
                class="btn btn-danger"
                data-bs-toggle="modal"
                data-bs-target="#confirmUserDeletionModal">
            <?php echo e(__('Delete Account')); ?>

        </button>
    </div>
</div>

<!-- Modal Bootstrap -->
<div class="modal fade"
     id="confirmUserDeletionModal"
     tabindex="-1"
     aria-labelledby="confirmUserDeletionModalLabel"
     aria-hidden="true"
     <?php if($errors->userDeletion->isNotEmpty()): ?> data-bs-show="true" <?php endif; ?>>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="confirmUserDeletionModalLabel">
                    <?php echo e(__('Are you sure you want to delete your account?')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="post" action="<?php echo e(route('user.profile.destroy')); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('delete'); ?>

                <div class="modal-body">
                    <p class="text-muted mb-4">
                        <?php echo e(__('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.')); ?>

                    </p>

                    <div class="mb-3">
                        <label for="password" class="visually-hidden"><?php echo e(__('Password')); ?></label>
                        <input type="password"
                               class="form-control <?php $__errorArgs = ['password', 'userDeletion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="password"
                               name="password"
                               placeholder="<?php echo e(__('Password')); ?>"
                               required>

                        <?php if($errors->userDeletion->get('password')): ?>
                            <div class="invalid-feedback">
                                <?php $__currentLoopData = $errors->userDeletion->get('password'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div><?php echo e($error); ?></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <?php echo e(__('Cancel')); ?>

                    </button>
                    <button type="submit" class="btn btn-danger">
                        <?php echo e(__('Delete Account')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Si il y a des erreurs, ouvrir automatiquement le modal
    <?php if($errors->userDeletion->isNotEmpty()): ?>
        var modal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
        modal.show();
    <?php endif; ?>
});
</script>
<?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/user/profile/partials/delete-user-form.blade.php ENDPATH**/ ?>