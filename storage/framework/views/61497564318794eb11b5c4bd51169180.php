<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détails de la société</h5>
                    <div>
                        <?php if(Auth::user()->isSuperAdmin()): ?>
                        <a href="<?php echo e(route('societes.edit', $societe->id)); ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('societes.index')); ?>" class="btn btn-secondary btn-sm">
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
                                    <td><?php echo e($societe->code); ?></td>
                                </tr>
                                <tr>
                                    <th>Nom :</th>
                                    <td><?php echo e($societe->nom); ?></td>
                                </tr>
                                <tr>
                                    <th>SIRET :</th>
                                    <td><?php echo e($societe->siret ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Statut :</th>
                                    <td>
                                        <?php if($societe->est_actif): ?>
                                            <span class="badge bg-success">Actif</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Coordonnées</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 30%">Adresse :</th>
                                    <td><?php echo e($societe->adresse ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Code postal :</th>
                                    <td><?php echo e($societe->code_postal ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Ville :</th>
                                    <td><?php echo e($societe->ville ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Pays :</th>
                                    <td><?php echo e($societe->pays ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Téléphone :</th>
                                    <td><?php echo e($societe->telephone ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Email :</th>
                                    <td><?php echo e($societe->email ?? 'N/A'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Clients associés</h6>
                            <?php if($societe->clients->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Téléphone</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $societe->clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($client->code); ?></td>
                                                <td><?php echo e($client->nom); ?></td>
                                                <td><?php echo e($client->email); ?></td>
                                                <td><?php echo e($client->telephone); ?></td>
                                                <td>
                                                    <?php if($client->est_actif): ?>
                                                        <span class="badge bg-success">Actif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Inactif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(route('clients.show', $client->id)); ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Aucun client associé à cette société.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Dossiers associés</h6>
                            <?php if($societe->dossiers->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Nom</th>
                                                <th>Client</th>
                                                <th>Exercice</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $societe->dossiers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dossier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($dossier->code); ?></td>
                                                <td><?php echo e($dossier->nom); ?></td>
                                                <td><?php echo e($dossier->client->nom ?? 'N/A'); ?></td>
                                                <td>
                                                    <?php if($dossier->exercice_debut && $dossier->exercice_fin): ?>
                                                        <?php echo e($dossier->exercice_debut->format('d/m/Y')); ?> - <?php echo e($dossier->exercice_fin->format('d/m/Y')); ?>

                                                    <?php else: ?>
                                                        N/A
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($dossier->est_cloture): ?>
                                                        <span class="badge bg-danger">Clôturé</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Actif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(route('dossiers.show', $dossier->id)); ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Aucun dossier associé à cette société.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Utilisateurs associés</h6>
                            <?php if($societe->users->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Client</th>
                                                <th>Rôle</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $societe->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($user->name); ?></td>
                                                <td><?php echo e($user->email); ?></td>
                                                <td><?php echo e($user->client->nom ?? 'N/A'); ?></td>
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
                                <p class="text-muted">Aucun utilisateur associé à cette société.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/societes/show.blade.php ENDPATH**/ ?>