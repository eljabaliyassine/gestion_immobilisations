<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-1"><?php echo e(__('Profile Information')); ?></h4>
        <p class="card-text text-muted"><?php echo e(__("Update your account's profile information and email address.")); ?></p>
    </div>

    <div class="card-body">
        <form method="post" action="<?php echo e(route('user.profile.update')); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('patch'); ?>

            <!-- Champ Nom -->
            <div class="mb-3">
                <label for="name" class="form-label"><?php echo e(__('Name')); ?></label>
                <input type="text"
                       class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       id="name"
                       name="name"
                       value="<?php echo e(old('name', $user->name)); ?>"
                       required
                       autofocus
                       autocomplete="name">

                <?php if($errors->get('name')): ?>
                    <div class="invalid-feedback">
                        <?php $__currentLoopData = $errors->get('name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div><?php echo e($error); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Champ Email -->
            <div class="mb-3">
                <label for="email" class="form-label"><?php echo e(__('Email')); ?></label>
                <input type="email"
                       class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       id="email"
                       name="email"
                       value="<?php echo e(old('email', $user->email)); ?>"
                       required
                       autocomplete="username">

                <?php if($errors->get('email')): ?>
                    <div class="invalid-feedback">
                        <?php $__currentLoopData = $errors->get('email'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div><?php echo e($error); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Vérification Email -->
            <?php if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail()): ?>
                <div class="alert alert-warning" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <?php echo e(__('Your email address is unverified.')); ?>

                        </div>
                        <div class="ms-3">
                            <form method="post" action="<?php echo e(route('verification.send')); ?>" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    <?php echo e(__('Click here to re-send the verification email.')); ?>

                                </button>
                            </form>
                        </div>
                    </div>

                    <?php if(session('status') === 'verification-link-sent'): ?>
                        <div class="mt-2 text-success">
                            <small><?php echo e(__('A new verification link has been sent to your email address.')); ?></small>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Boutons d'action -->
            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary">
                    <?php echo e(__('Save')); ?>

                </button>

                <?php if(session('status') === 'profile-updated'): ?>
                    <div class="text-success">
                        <small><?php echo e(__('Saved.')); ?></small>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
<?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/user/profile/partials/update-profile-information-form.blade.php ENDPATH**/ ?>