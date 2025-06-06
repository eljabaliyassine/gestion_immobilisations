<!-- edit.blade.php -->


<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <!-- Hero Section avec Gradient -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-gradient-primary rounded-4 p-4 text-white position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 opacity-25">
                    <i class="fas fa-user-circle" style="font-size: 8rem;"></i>
                </div>
                <div class="position-relative">
                    <h1 class="display-5 fw-bold mb-2">
                        <i class="fas fa-user-edit me-3"></i>
                        <?php echo e(__('Mon Profil')); ?>

                    </h1>
                    <p class="lead mb-0 opacity-90">
                        <?php echo e(__('Gérez vos informations personnelles et paramètres de sécurité')); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Colonne principale -->
        <div class="col-xl-8 col-lg-7">
            <!-- Informations du profil -->
            <div class="card border-0 shadow-sm mb-4 hover-card">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-user text-primary"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1 fw-semibold"><?php echo e(__('Informations Personnelles')); ?></h5>
                            <small class="text-muted"><?php echo e(__('Mettez à jour vos informations de profil')); ?></small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <?php echo $__env->make('user.profile.partials.update-profile-information-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>

            <!-- Mot de passe -->
            <div class="card border-0 shadow-sm mb-4 hover-card">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-key text-warning"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1 fw-semibold"><?php echo e(__('Sécurité du Compte')); ?></h5>
                            <small class="text-muted"><?php echo e(__('Modifiez votre mot de passe')); ?></small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <?php echo $__env->make('user.profile.partials.update-password-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
        </div>

        <!-- Sidebar avec informations utiles -->
        <div class="col-xl-4 col-lg-5">
            <!-- Statistiques du profil -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="card-title fw-semibold mb-3">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        <?php echo e(__('Activité du Compte')); ?>

                    </h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-primary bg-opacity-10 rounded-3">
                                <h4 class="text-primary fw-bold mb-1"><?php echo e(Auth::user()->created_at->diffInDays()); ?></h4>
                                <small class="text-muted"><?php echo e(__('Jours')); ?></small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-success bg-opacity-10 rounded-3">
                                <h4 class="text-success fw-bold mb-1"><?php echo e(Auth::user()->email_verified_at ? '✅' : '❌'); ?></h4>                                <small class="text-muted"><?php echo e(__('Vérifié')); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conseils de sécurité -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="card-title fw-semibold mb-3">
                        <i class="fas fa-shield-alt text-success me-2"></i>
                        <?php echo e(__('Conseils de Sécurité')); ?>

                    </h6>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0 py-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small><?php echo e(__('Utilisez un mot de passe fort')); ?></small>
                        </div>
                        <div class="list-group-item border-0 px-0 py-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small><?php echo e(__('Vérifiez votre email')); ?></small>
                        </div>
                        <div class="list-group-item border-0 px-0 py-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small><?php echo e(__('Mettez à jour régulièrement')); ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="card-title fw-semibold mb-3">
                        <i class="fas fa-bolt me-2" style="color: var(--warning-color);"></i>
                        <?php echo e(__('Actions Rapides')); ?>

                    </h6>
                    <div class="d-grid gap-2">
                        
                        <a href="<?php echo e(route('user.download.data')); ?>" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-download me-2"></i>
                            <?php echo e(__('Télécharger mes données')); ?>

                        </a>
                        
                        <a href="<?php echo e(route('user.login.history')); ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-history me-2"></i>
                            <?php echo e(__('Historique des connexions')); ?>

                        </a>
                        
                        <a href="<?php echo e(route('user.help.center')); ?>" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-question-circle me-2"></i>
                            <?php echo e(__('Centre d\'aide')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles personnalisés pour améliorer l'apparence */
.bg-gradient-primary {
    background: linear-gradient(135deg, #51697B 0%, #0c2e46 100%);
}

.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.icon-wrapper {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card {
    border-radius: 12px;
}

.rounded-4 {
    border-radius: 1rem !important;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

/* Animation pour les icônes */
.icon-wrapper i {
    transition: transform 0.2s ease;
}

.hover-card:hover .icon-wrapper i {
    transform: scale(1.1);
}

/* Responsive amélioré */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .display-5 {
        font-size: 2rem;
    }

    .card-body {
        padding: 1.5rem !important;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/user/profile/edit.blade.php ENDPATH**/ ?>