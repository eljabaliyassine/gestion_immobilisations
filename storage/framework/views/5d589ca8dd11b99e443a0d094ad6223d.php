<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0"><?php echo e(__('Centre d\'aide')); ?></h1>
                    <p class="text-muted mb-0"><?php echo e(__('Trouvez des réponses à vos questions')); ?></p>
                </div>
                <a href="<?php echo e(route('user.profile.edit')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    <?php echo e(__('Retour au profil')); ?>

                </a>
            </div>

            <!-- Barre de recherche -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" placeholder="<?php echo e(__('Rechercher dans l\'aide...')); ?>">
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <button class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>
                                <?php echo e(__('Rechercher')); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sections d'aide -->
            <div class="row">
                <?php $__currentLoopData = $helpSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle p-3 me-3" style="background-color: var(--light-color);">
                                        <i class="<?php echo e($section['icon']); ?> fa-lg" style="color: var(--primary-color);"></i>
                                    </div>
                                    <h5 class="card-title mb-0"><?php echo e($section['title']); ?></h5>
                                </div>

                                <ul class="list-unstyled">
                                    <?php $__currentLoopData = $section['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="mb-2">
                                            <a href="#" class="text-decoration-none d-flex align-items-center">
                                                <i class="fas fa-chevron-right fa-xs me-2" style="color: var(--primary-color);"></i>
                                                <span class="small"><?php echo e($item); ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Contact support -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body text-center py-5">
                    <i class="fas fa-headset fa-3x mb-3" style="color: var(--info-color);"></i>
                    <h5 class="mb-3"><?php echo e(__('Besoin d\'aide supplémentaire ?')); ?></h5>
                    <p class="text-muted mb-4"><?php echo e(__('Notre équipe de support est là pour vous aider')); ?></p>
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <a href="mailto:support@example.com" class="btn btn-outline-primary">
                                    <i class="fas fa-envelope me-2"></i>
                                    <?php echo e(__('Envoyer un email')); ?>

                                </a>
                                <a href="#" class="btn btn-outline-info">
                                    <i class="fas fa-comments me-2"></i>
                                    <?php echo e(__('Chat en direct')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ rapide -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-question-circle me-2" style="color: var(--warning-color);"></i>
                        <?php echo e(__('Questions fréquentes')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item border-0 mb-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    <?php echo e(__('Comment puis-je changer mon mot de passe ?')); ?>

                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <?php echo e(__('Rendez-vous dans votre profil, section "Modifier le mot de passe", entrez votre mot de passe actuel puis le nouveau.')); ?>

                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    <?php echo e(__('Comment télécharger mes données ?')); ?>

                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <?php echo e(__('Utilisez le bouton "Télécharger mes données" dans la section Actions Rapides de votre profil.')); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}
.btn-primary:hover {
    background-color: var(--secondary-color);
    border-color: var(--secondary-color);
}
.btn-outline-primary {
    border-color: var(--primary-color);
    color: var(--primary-color);
}
.btn-outline-primary:hover {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}
.btn-outline-info {
    border-color: var(--info-color);
    color: var(--info-color);
}
.btn-outline-info:hover {
    background-color: var(--info-color);
    border-color: var(--info-color);
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/user/help-center.blade.php ENDPATH**/ ?>