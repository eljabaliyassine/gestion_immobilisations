<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Gestion des Sociétés</h2>
                    <p class="text-muted mb-0">Gérez et suivez toutes vos sociétés partenaires</p>
                </div>
                <?php if(Auth::user()->isSuperAdmin()): ?>
                <a href="<?php echo e(route('societes.create')); ?>" class="btn btn-primary btn-lg px-4 py-2 shadow-sm">
                    <i class="fas fa-plus me-2"></i>Nouvelle société
                </a>
                <?php endif; ?>
            </div>

            <!-- Alerts Section -->
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Main Card -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-4 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-20 rounded-3 p-2 me-3">
                                <i class="fas fa-building fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-semibold">Liste des Sociétés</h5>
                                <small class="opacity-75"><?php echo e($societes->count()); ?> société(s) enregistrée(s)</small>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="card-body p-0">
                    <?php if($societes->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 py-3 px-4 fw-semibold text-uppercase text-muted small">
                                            <i class="fas fa-hashtag me-2 text-primary"></i>Code
                                        </th>
                                        <th class="border-0 py-3 px-4 fw-semibold text-uppercase text-muted small">
                                            <i class="fas fa-building me-2 text-primary"></i>Nom
                                        </th>
                                        <th class="border-0 py-3 px-4 fw-semibold text-uppercase text-muted small">
                                            <i class="fas fa-envelope me-2 text-primary"></i>Email
                                        </th>
                                        <th class="border-0 py-3 px-4 fw-semibold text-uppercase text-muted small">
                                            <i class="fas fa-phone me-2 text-primary"></i>Téléphone
                                        </th>
                                        <th class="border-0 py-3 px-4 fw-semibold text-uppercase text-muted small">
                                            <i class="fas fa-toggle-on me-2 text-primary"></i>Statut
                                        </th>
                                        <th class="border-0 py-3 px-4 fw-semibold text-uppercase text-muted small text-center">
                                            <i class="fas fa-cogs me-2 text-primary"></i>Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $societes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $societe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-bottom border-light-subtle hover-row">
                                        <td class="px-4 py-3">
                                            <span class="badge bg-light text-dark fw-normal px-3 py-2 rounded-pill">
                                                <?php echo e($societe->code); ?>

                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="fas fa-building text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold"><?php echo e($societe->nom); ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="mailto:<?php echo e($societe->email); ?>" class="text-decoration-none text-muted hover-primary">
                                                <i class="fas fa-envelope me-2"></i><?php echo e($societe->email); ?>

                                            </a>
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="tel:<?php echo e($societe->telephone); ?>" class="text-decoration-none text-muted hover-primary">
                                                <i class="fas fa-phone me-2"></i><?php echo e($societe->telephone); ?>

                                            </a>
                                        </td>
                                        <td class="px-4 py-3">
                                            <?php if($societe->est_actif): ?>
                                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                                    <i class="fas fa-check-circle me-1"></i>Actif
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                                    <i class="fas fa-times-circle me-1"></i>Inactif
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="btn-group shadow-sm" role="group">
                                                <a href="<?php echo e(route('societes.show', $societe->id)); ?>"
                                                   class="btn btn-outline-info btn-sm rounded-start"
                                                   title="Voir les détails"
                                                   data-bs-toggle="tooltip">
                                                   <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if(Auth::user()->isSuperAdmin()): ?>
                                                <a href="<?php echo e(route('societes.edit', $societe->id)); ?>"
                                                   class="btn btn-outline-warning btn-sm"
                                                   title="Modifier"
                                                   data-bs-toggle="tooltip">
                                                   <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button"
                                                    class="btn btn-outline-danger btn-sm rounded-end"
                                                    title="Supprimer"
                                                    data-bs-toggle="tooltip"
                                                    onclick="confirmDelete('<?php echo e($societe->id); ?>', '<?php echo e($societe->nom); ?>')"
                                                >
                                                    <i class="fas fa-unlink"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Hidden form for deletion -->
                                            <?php if(Auth::user()->isSuperAdmin()): ?>
                                            <form id="delete-form-<?php echo e($societe->id); ?>"
                                                  action="<?php echo e(route('societes.destroy', $societe->id)); ?>"
                                                  method="POST"
                                                  class="d-none">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                            </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-building text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                            </div>
                            <h5 class="text-muted mb-2">Aucune société trouvée</h5>
                            <p class="text-muted mb-4">Commencez par ajouter votre première société pour gérer votre portefeuille.</p>
                            <?php if(Auth::user()->isSuperAdmin()): ?>
                            <a href="<?php echo e(route('societes.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Ajouter une société
                            </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #51697B 0%, #0c2e46 100%);
}

.hover-row:hover {
    background-color: rgba(0, 123, 255, 0.02);
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

.hover-primary:hover {
    color: var(--bs-primary) !important;
    transition: color 0.2s ease;
}

.btn-group .btn {
    border: 1px solid #dee2e6;
}

.btn-group .btn:hover {
    z-index: 2;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.card {
    transition: all 0.3s ease;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-weight: 500;
    letter-spacing: 0.025em;
}

.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-danger-subtle {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

@media print {
    .btn, .card-header .d-flex > div:last-child {
        display: none !important;
    }
}
</style>

<!-- JavaScript for Enhanced Functionality -->
<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Enhanced delete confirmation
function confirmDelete(id, nom) {
    Swal.fire({
        title: 'Confirmer la suppression',
        html: `Êtes-vous sûr de vouloir supprimer la société <strong>"${nom}"</strong> ?<br><small class="text-muted">Cette action est irréversible.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-1"></i>Supprimer',
        cancelButtonText: '<i class="fas fa-times me-1"></i>Annuler',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}

// Export functionality (placeholder)
function exportToExcel() {
    // Placeholder for export functionality
    Swal.fire({
        title: 'Fonctionnalité en développement',
        text: 'L\'export Excel sera disponible prochainement.',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}
</script>

<!-- Include SweetAlert2 for better modals -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/societes/index.blade.php ENDPATH**/ ?>