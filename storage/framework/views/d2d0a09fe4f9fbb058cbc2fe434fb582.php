<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Liste des contrats</h5>
                    <div>
                        <a href="<?php echo e(route('contrat.exportCsv.csv')); ?>" class="btn btn-success me-2">
                            <i class="fas fa-file-csv me-2"></i>Exporter CSV
                        </a>
                        <a href="<?php echo e(route('contrats.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouveau contrat
                        </a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success mx-4 mt-3">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger mx-4 mt-3">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Référence</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Prestataire/Fournisseur</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Période</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Montant</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Périodicité</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Prochaine échéance</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Immobilisations</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $contrats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contrat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex px-3 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm"><?php echo e($contrat->reference); ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-<?php echo e($contrat->type == 'maintenance' ? 'info' : ($contrat->type == 'location' ? 'primary' : ($contrat->type == 'leasing' ? 'warning' : 'secondary'))); ?>">
                                                <?php echo e(ucfirst($contrat->type)); ?>

                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm font-weight-bold mb-0">
                                                <?php echo e($contrat->prestataire ? $contrat->prestataire->nom : '-'); ?>

                                                <?php if($contrat->fournisseur): ?>
                                                    <br><small class="text-muted"><?php echo e($contrat->fournisseur->nom); ?></small>
                                                <?php endif; ?>
                                            </p>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm font-weight-bold mb-0">
                                                <?php echo e($contrat->date_debut ? $contrat->date_debut->format('d/m/Y') : '-'); ?>

                                                <?php if($contrat->date_fin): ?>
                                                    au <?php echo e($contrat->date_fin->format('d/m/Y')); ?>

                                                <?php endif; ?>
                                            </p>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm font-weight-bold mb-0"><?php echo e(number_format($contrat->montant_periodique, 2, ',', ' ')); ?> DH</p>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm font-weight-bold mb-0"><?php echo e(ucfirst($contrat->periodicite)); ?></p>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm font-weight-bold mb-0">
                                                <?php echo e($contrat->date_prochaine_echeance ? $contrat->date_prochaine_echeance->format('d/m/Y') : '-'); ?>

                                            </p>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-<?php echo e($contrat->statut == 'actif' ? 'success' : ($contrat->statut == 'inactif' ? 'secondary' : 'danger')); ?>">
                                                <?php echo e(ucfirst($contrat->statut)); ?>

                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm font-weight-bold mb-0"><?php echo e($contrat->immobilisations->count()); ?></p>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex">
                                                <a href="<?php echo e(route('contrats.show', $contrat->id)); ?>" class="btn btn-sm btn-info mx-1" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('contrats.edit', $contrat->id)); ?>" class="btn btn-sm btn-warning mx-1" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?php echo e(route('contrats.immobilisations', $contrat->id)); ?>" class="btn btn-sm btn-primary mx-1" title="Immobilisations">
                                                    <i class="fas fa-link"></i>
                                                </a>
                                                <?php if($contrat->type == 'leasing'): ?>
                                                    <a href="<?php echo e(route('contrats.echeances', $contrat->id)); ?>" class="btn btn-sm btn-success mx-1" title="Échéances">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <form action="<?php echo e(route('contrats.destroy', $contrat->id)); ?>" method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-danger mx-1" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce contrat ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="10" class="text-center py-4">Aucun contrat trouvé</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <?php echo e($contrats->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/contrats/index.blade.php ENDPATH**/ ?>