<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-gradient-primary text-white pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-white fw-bold">
                            <i class="fas fa-file-contract me-3"></i>Détails du contrat
                        </h4>
                        <p class="mb-0 text-white-75 small"><?php echo e($contrat->reference); ?></p>
                    </div>
                    <div>
                        <a href="<?php echo e(route('contrats.edit', $contrat->id)); ?>" class="btn btn-warning btn-sm shadow-sm me-2">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="<?php echo e(route('contrats.index')); ?>" class="btn btn-light btn-sm shadow-sm">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-light border-0 pb-0">
                                    <h6 class="mb-0 text-dark fw-bold">
                                        <i class="fas fa-info-circle text-primary me-2"></i>Informations du contrat
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Référence</span>
                                        </div>
                                        <div class="col-md-8">
                                            <span class="fw-bold"><?php echo e($contrat->reference); ?></span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Type</span>
                                        </div>
                                        <div class="col-md-8">
                                            <span class="badge bg-<?php echo e($contrat->type == 'maintenance' ? 'info' : ($contrat->type == 'location' ? 'primary' : ($contrat->type == 'leasing' ? 'warning' : 'secondary'))); ?> px-3 py-2 rounded-pill">
                                                <i class="fas fa-<?php echo e($contrat->type == 'maintenance' ? 'tools' : ($contrat->type == 'location' ? 'home' : ($contrat->type == 'leasing' ? 'handshake' : 'file'))); ?> me-1"></i>
                                                <?php echo e(ucfirst($contrat->type)); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Période</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                                <span>
                                                    <?php if($contrat->date_debut): ?>
                                                        <?php if(is_string($contrat->date_debut)): ?>
                                                            <?php echo e(\Carbon\Carbon::parse($contrat->date_debut)->format('d/m/Y')); ?>

                                                        <?php else: ?>
                                                            <?php echo e($contrat->date_debut->format('d/m/Y')); ?>

                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>

                                                    <?php if($contrat->date_fin): ?>
                                                        <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                                        <?php if(is_string($contrat->date_fin)): ?>
                                                            <?php echo e(\Carbon\Carbon::parse($contrat->date_fin)->format('d/m/Y')); ?>

                                                        <?php else: ?>
                                                            <?php echo e($contrat->date_fin->format('d/m/Y')); ?>

                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Montant</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-money-bill-wave text-success me-2"></i>
                                                <span class="fw-bold text-success"><?php echo e(number_format($contrat->montant_periodique ?? 0, 2, ',', ' ')); ?> DH</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Périodicité</span>
                                        </div>
                                        <div class="col-md-8">
                                            <span class="badge bg-light text-dark border"><?php echo e(ucfirst($contrat->periodicite ?? '-')); ?></span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Statut</span>
                                        </div>
                                        <div class="col-md-8">
                                            <span class="badge bg-<?php echo e($contrat->statut == 'actif' ? 'success' : ($contrat->statut == 'inactif' ? 'warning' : 'danger')); ?> px-3 py-2 rounded-pill">
                                                <i class="fas fa-<?php echo e($contrat->statut == 'actif' ? 'check-circle' : ($contrat->statut == 'inactif' ? 'pause-circle' : 'times-circle')); ?> me-1"></i>
                                                <?php echo e(ucfirst($contrat->statut)); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-light border-0 pb-0">
                                    <h6 class="mb-0 text-dark fw-bold">
                                        <i class="fas fa-users text-primary me-2"></i>Prestataire et fournisseur
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Prestataire</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-tie text-primary me-2"></i>
                                                <span class="fw-bold"><?php echo e($contrat->prestataire ? $contrat->prestataire->nom : '-'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Fournisseur</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-building text-primary me-2"></i>
                                                <span class="fw-bold"><?php echo e($contrat->fournisseur ? $contrat->fournisseur->nom : '-'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Créé le</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-plus-circle text-success me-2"></i>
                                                <span class="small">
                                                    <?php if($contrat->created_at): ?>
                                                        <?php if(is_string($contrat->created_at)): ?>
                                                            <?php echo e(\Carbon\Carbon::parse($contrat->created_at)->format('d/m/Y H:i')); ?>

                                                        <?php else: ?>
                                                            <?php echo e($contrat->created_at->format('d/m/Y H:i')); ?>

                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Modifié le</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-edit text-warning me-2"></i>
                                                <span class="small">
                                                    <?php if($contrat->updated_at): ?>
                                                        <?php if(is_string($contrat->updated_at)): ?>
                                                            <?php echo e(\Carbon\Carbon::parse($contrat->updated_at)->format('d/m/Y H:i')); ?>

                                                        <?php else: ?>
                                                            <?php echo e($contrat->updated_at->format('d/m/Y H:i')); ?>

                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light border-0 pb-0">
                                    <h6 class="mb-0 text-dark fw-bold">
                                        <i class="fas fa-file-alt text-primary me-2"></i>Description
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="bg-light p-3 rounded">
                                        <p class="mb-0 text-muted"><?php echo e($contrat->description ?? 'Aucune description'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light border-0 pb-0 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-dark fw-bold">
                                        <i class="fas fa-boxes text-primary me-2"></i>
                                        Immobilisations liées
                                        <span class="badge bg-primary rounded-pill ms-2"><?php echo e($immobilisations->count()); ?></span>
                                    </h6>
                                    <a href="<?php echo e(route('contrats.immobilisations', $contrat->id)); ?>" class="btn btn-primary btn-sm shadow-sm">
                                        <i class="fas fa-link me-2"></i>Gérer les immobilisations
                                    </a>
                                </div>
                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-items-center mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">
                                                        <i class="fas fa-barcode me-1"></i>Code
                                                    </th>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">
                                                        <i class="fas fa-tag me-1"></i>Désignation
                                                    </th>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">
                                                        <i class="fas fa-layer-group me-1"></i>Famille
                                                    </th>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">
                                                        <i class="fas fa-map-marker-alt me-1"></i>Localisation
                                                    </th>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">
                                                        <i class="fas fa-cogs me-1"></i>Actions
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__empty_1 = true; $__currentLoopData = $immobilisations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $immobilisation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <tr>
                                                        <td class="align-middle">
                                                            <div class="d-flex px-3 py-1">
                                                                <div class="d-flex flex-column justify-content-center">
                                                                    <h6 class="mb-0 text-sm fw-bold"><?php echo e($immobilisation->code_barre ?? $immobilisation->code); ?></h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle">
                                                            <p class="text-sm font-weight-bold mb-0"><?php echo e($immobilisation->designation); ?></p>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="badge bg-light text-dark border"><?php echo e($immobilisation->famille ? $immobilisation->famille->libelle : '-'); ?></span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                                <span class="text-sm">
                                                                    <?php echo e($immobilisation->site ? $immobilisation->site->libelle : '-'); ?>

                                                                    <?php if($immobilisation->service): ?>
                                                                        <br><small class="text-muted"><?php echo e($immobilisation->service->libelle); ?></small>
                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle">
                                                            <div class="d-flex gap-2">
                                                                <a href="<?php echo e(route('immobilisations.show', $immobilisation->id)); ?>" class="btn btn-sm btn-info shadow-sm" title="Voir">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <form action="<?php echo e(route('contrats.detachImmobilisation', [$contrat->id, $immobilisation->id])); ?>" method="POST" class="d-inline">
                                                                    <?php echo csrf_field(); ?>
                                                                    <?php echo method_field('DELETE'); ?>
                                                                    <button type="submit" class="btn btn-sm btn-danger shadow-sm" title="Retirer" onclick="return confirm('Êtes-vous sûr de vouloir retirer cette immobilisation du contrat ?')">
                                                                        <i class="fas fa-unlink"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center py-4">
                                                            <div class="text-muted">
                                                                <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                                                <p class="mb-0">Aucune immobilisation liée à ce contrat</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if($contrat->type == 'leasing' && isset($detailCreditBail) && isset($echeances)): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-gradient-warning text-white pb-0 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-white fw-bold">
                                        <i class="fas fa-credit-card me-2"></i>Détails du crédit-bail
                                    </h6>
                                    <a href="<?php echo e(route('contrats.echeances', $contrat->id)); ?>" class="btn btn-light btn-sm shadow-sm">
                                        <i class="fas fa-calendar-alt me-2"></i>Gérer les échéances
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="card bg-light border-0 h-100">
                                                <div class="card-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <span class="text-muted small text-uppercase fw-bold">Durée (mois)</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <span class="fw-bold"><?php echo e($detailCreditBail->duree_mois); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <span class="text-muted small text-uppercase fw-bold">Valeur résiduelle</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <span class="fw-bold text-success"><?php echo e(number_format($detailCreditBail->valeur_residuelle, 2, ',', ' ')); ?> DH</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <span class="text-muted small text-uppercase fw-bold">Périodicité</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <span class="badge bg-primary"><?php echo e(ucfirst($detailCreditBail->periodicite)); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-light border-0 h-100">
                                                <div class="card-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <span class="text-muted small text-uppercase fw-bold">Montant redevance</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <span class="fw-bold text-success"><?php echo e(number_format($detailCreditBail->montant_redevance_periodique, 2, ',', ' ')); ?> DH</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <span class="text-muted small text-uppercase fw-bold">Taux d'intérêt</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <span class="fw-bold text-info"><?php echo e($detailCreditBail->taux_interet_periodique ? number_format($detailCreditBail->taux_interet_periodique, 2, ',', ' ') . ' %' : '-'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <span class="text-muted small text-uppercase fw-bold">Montant total</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <span class="fw-bold text-primary"><?php echo e(number_format($detailCreditBail->montant_total_redevances, 2, ',', ' ')); ?> DH</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-calendar-check text-primary me-2"></i>
                                            <h6 class="mb-0 fw-bold">Prochaines échéances</h6>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="fw-bold">N°</th>
                                                        <th class="fw-bold">Date</th>
                                                        <th class="fw-bold">Montant</th>
                                                        <th class="fw-bold">Intérêt</th>
                                                        <th class="fw-bold">Capital</th>
                                                        <th class="fw-bold">Capital restant</th>
                                                        <th class="fw-bold">Statut</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $echeances->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $echeance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td class="fw-bold"><?php echo e($echeance->numero_echeance); ?></td>
                                                            <td>
                                                                <?php if($echeance->date_echeance): ?>
                                                                    <?php if(is_string($echeance->date_echeance)): ?>
                                                                        <?php echo e(\Carbon\Carbon::parse($echeance->date_echeance)->format('d/m/Y')); ?>

                                                                    <?php else: ?>
                                                                        <?php echo e($echeance->date_echeance->format('d/m/Y')); ?>

                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    -
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="fw-bold"><?php echo e(number_format($echeance->montant_redevance, 2, ',', ' ')); ?></td>
                                                            <td><?php echo e(number_format($echeance->part_interet, 2, ',', ' ')); ?></td>
                                                            <td><?php echo e(number_format($echeance->part_capital, 2, ',', ' ')); ?></td>
                                                            <td><?php echo e(number_format($echeance->capital_restant_du, 2, ',', ' ')); ?></td>
                                                            <td>
                                                                <span class="badge bg-<?php echo e($echeance->statut == 'payee' ? 'success' : ($echeance->statut == 'en_retard' ? 'danger' : 'warning')); ?> px-2 py-1 rounded-pill">
                                                                    <i class="fas fa-<?php echo e($echeance->statut == 'payee' ? 'check' : ($echeance->statut == 'en_retard' ? 'exclamation-triangle' : 'clock')); ?> me-1"></i>
                                                                    <?php echo e($echeance->statut == 'payee' ? 'Payée' : ($echeance->statut == 'en_retard' ? 'En retard' : 'À payer')); ?>

                                                                </span>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($contrat->document_path): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light border-0 pb-0">
                                    <h6 class="mb-0 text-dark fw-bold">
                                        <i class="fas fa-paperclip text-primary me-2"></i>Document
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="p-4">
                                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                        <div>
                                            <a href="<?php echo e(asset('storage/' . $contrat->document_path)); ?>" target="_blank" class="btn btn-info btn-lg shadow-sm">
                                                <i class="fas fa-download me-2"></i>Télécharger le document
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #51697B 0%, #0c2e46 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.badge {
    font-size: 0.75em;
}

.text-white-75 {
    color: rgba(255, 255, 255, 0.75) !important;
}

.gap-2 {
    gap: 0.5rem;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.shadow-lg {
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/contrats/show.blade.php ENDPATH**/ ?>