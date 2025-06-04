<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détails du dossier</h5>
                    <div>
                        <?php if(Auth::user()->isSuperAdmin() || (Auth::user()->isAdmin() && Auth::user()->client_id == $dossier->client_id)): ?>
                        <a href="<?php echo e(route('dossiers.edit', $dossier->id)); ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('dossiers.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Informations générales</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 30%">Code :</th>
                                    <td><?php echo e($dossier->code); ?></td>
                                </tr>
                                <tr>
                                    <th>Nom :</th>
                                    <td><?php echo e($dossier->nom); ?></td>
                                </tr>
                                <tr>
                                    <th>Libellé :</th>
                                    <td><?php echo e($dossier->libelle); ?></td>
                                </tr>
                                <tr>
                                    <th>Société :</th>
                                    <td><?php echo e($dossier->societe->nom ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Client :</th>
                                    <td><?php echo e($dossier->client->nom ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Statut :</th>
                                    <td>
                                        <?php if($dossier->est_cloture): ?>
                                            <span class="badge bg-danger">Clôturé</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Actif</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Exercice comptable</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 30%">Début d'exercice :</th>
                                    <td><?php echo e($dossier->exercice_debut ? $dossier->exercice_debut->format('d/m/Y') : 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Fin d'exercice :</th>
                                    <td><?php echo e($dossier->exercice_fin ? $dossier->exercice_fin->format('d/m/Y') : 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Date de clôture :</th>
                                    <td><?php echo e($dossier->date_cloture ? $dossier->date_cloture->format('d/m/Y') : 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Description :</th>
                                    <td><?php echo e($dossier->description ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Créé le :</th>
                                    <td><?php echo e($dossier->created_at->format('d/m/Y H:i')); ?></td>
                                </tr>
                                <tr>
                                    <th>Mis à jour le :</th>
                                    <td><?php echo e($dossier->updated_at->format('d/m/Y H:i')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Utilisateurs associés</h6>
                            <?php if($dossier->users->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Rôle</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $dossier->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($user->name); ?></td>
                                                <td><?php echo e($user->email); ?></td>
                                                <td><?php echo e($user->role->name ?? 'N/A'); ?></td>
                                                <td>
                                                    <a href="<?php echo e(route('users.show', $user->id)); ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Aucun utilisateur associé à ce dossier.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Immobilisations</h6>
                            <?php if($dossier->immobilisations->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Désignation</th>
                                                <th>Date acquisition</th>
                                                <th>Valeur acquisition</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $dossier->immobilisations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $immobilisation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($immobilisation->code); ?></td>
                                                <td><?php echo e($immobilisation->designation); ?></td>
                                                <td><?php echo e($immobilisation->date_acquisition ? $immobilisation->date_acquisition->format('d/m/Y') : 'N/A'); ?></td>
                                                <td><?php echo e(number_format($immobilisation->valeur_acquisition, 2, ',', ' ')); ?> DH</td>
                                                <td>
                                                    <?php if($immobilisation->est_actif): ?>
                                                        <span class="badge bg-success">Actif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Inactif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(route('immobilisations.show', $immobilisation->id)); ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Aucune immobilisation associée à ce dossier.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Contrats</h6>
                            <?php if($dossier->contrats->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Référence</th>
                                                <th>Type</th>
                                                <th>Date début</th>
                                                <th>Date fin</th>
                                                <th>Montant</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $dossier->contrats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contrat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($contrat->reference); ?></td>
                                                <td><?php echo e($contrat->type); ?></td>
                                                <td><?php echo e($contrat->date_debut ? $contrat->date_debut->format('d/m/Y') : 'N/A'); ?></td>
                                                <td><?php echo e($contrat->date_fin ? $contrat->date_fin->format('d/m/Y') : 'N/A'); ?></td>
                                                <td><?php echo e(number_format($contrat->montant, 2, ',', ' ')); ?> DH</td>
                                                <td>
                                                    <a href="<?php echo e(route('contrats.show', $contrat->id)); ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Aucun contrat associé à ce dossier.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/dossiers/show.blade.php ENDPATH**/ ?>