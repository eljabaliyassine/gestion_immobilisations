

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0"><?php echo e(__('Historique des connexions')); ?></h1>
                    <p class="text-muted mb-0"><?php echo e(__('Consultez vos dernières connexions')); ?></p>
                </div>
                <a href="<?php echo e(route('user.profile.edit')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    <?php echo e(__('Retour au profil')); ?>

                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2" style="color: var(--secondary-color);"></i>
                        <?php echo e(__('Dernières connexions')); ?>

                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if($loginHistory->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th><?php echo e(__('Date & Heure')); ?></th>
                                        <th><?php echo e(__('Adresse IP')); ?></th>
                                        <th><?php echo e(__('Navigateur')); ?></th>
                                        <th><?php echo e(__('Statut')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $loginHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $login): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-medium"><?php echo e($login['login_at']->format('d/m/Y')); ?></span>
                                                    <small class="text-muted"><?php echo e($login['login_at']->format('H:i:s')); ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <code class="bg-light px-2 py-1 rounded"><?php echo e($login['ip']); ?></code>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if(str_contains($login['user_agent'], 'iPhone')): ?>
                                                        <i class="fab fa-apple me-2 text-secondary"></i>
                                                    <?php elseif(str_contains($login['user_agent'], 'Chrome')): ?>
                                                        <i class="fab fa-chrome me-2 text-warning"></i>
                                                    <?php else: ?>
                                                        <i class="fas fa-globe me-2 text-info"></i>
                                                    <?php endif; ?>
                                                    <span class="small"><?php echo e(Str::limit($login['user_agent'], 50)); ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($login['status'] === 'success'): ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>
                                                        <?php echo e(__('Réussie')); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times me-1"></i>
                                                        <?php echo e(__('Échouée')); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted"><?php echo e(__('Aucun historique disponible')); ?></h5>
                            <p class="text-muted"><?php echo e(__('Vos connexions futures apparaîtront ici')); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Informations de sécurité -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="card-title text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo e(__('Sécurité')); ?>

                    </h6>
                    <p class="card-text text-muted mb-0">
                        <?php echo e(__('Si vous remarquez une activité suspecte, changez immédiatement votre mot de passe et contactez notre support.')); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.badge.bg-success {
    background-color: var(--success-color) !important;
}
.badge.bg-danger {
    background-color: var(--danger-color) !important;
}
.text-warning {
    color: var(--warning-color) !important;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/user/login-history.blade.php ENDPATH**/ ?>