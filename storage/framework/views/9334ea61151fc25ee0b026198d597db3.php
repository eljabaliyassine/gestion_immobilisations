<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Détails de l'immobilisation</h5>
                    <div>
                        <a href="<?php echo e(route('immobilisations.edit', $immobilisation->id)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="<?php echo e(route('immobilisations.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-sm">Informations générales</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <th class="w-30">Code barre</th>
                                            <td><?php echo e($immobilisation->code_barre); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Désignation</th>
                                            <td><?php echo e($immobilisation->designation); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td><?php echo e($immobilisation->description ?? 'N/A'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Famille</th>
                                            <td><?php echo e($immobilisation->famille->libelle ?? 'N/A'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Statut</th>
                                            <td>
                                                <span class="badge bg-<?php echo e($immobilisation->statut == 'En service' ? 'success' : ($immobilisation->statut == 'Cédé' || $immobilisation->statut == 'Rebut' ? 'danger' : 'secondary')); ?>">
                                                    <?php echo e($immobilisation->statut); ?>

                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Numéro de série</th>
                                            <td><?php echo e($immobilisation->numero_serie ?? 'N/A'); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-sm">Localisation</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <th class="w-30">Site</th>
                                            <td><?php echo e($immobilisation->site->libelle ?? 'N/A'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Service</th>
                                            <td><?php echo e($immobilisation->service->libelle ?? 'N/A'); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <h6 class="text-uppercase text-sm mt-4">Acquisition</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <th class="w-30">Date acquisition</th>
                                            <td><?php echo e($immobilisation->date_acquisition ? $immobilisation->date_acquisition->format('d/m/Y') : 'N/A'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Date mise en service</th>
                                            <td><?php echo e($immobilisation->date_mise_service ? $immobilisation->date_mise_service->format('d/m/Y') : 'N/A'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Valeur acquisition</th>
                                            <td><?php echo e(number_format($immobilisation->valeur_acquisition, 2, ',', ' ')); ?> DH</td>
                                        </tr>
                                        <tr>
                                            <th>Fournisseur</th>
                                            <td><?php echo e($immobilisation->fournisseur->nom ?? 'N/A'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>N° Facture</th>
                                            <td><?php echo e($immobilisation->numero_facture ?? 'N/A'); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-sm">Amortissement</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <th class="w-30">Base amortissement</th>
                                            <td><?php echo e(number_format($immobilisation->base_amortissement, 2, ',', ' ')); ?> DH</td>
                                        </tr>
                                        <tr>
                                            <th>Méthode</th>
                                            <td><?php echo e($immobilisation->methode_amortissement == 'lineaire' ? 'Linéaire' : 'Dégressif'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Durée</th>
                                            <td><?php echo e($immobilisation->duree_amortissement); ?> ans</td>
                                        </tr>
                                        <tr>
                                            <th>Valeur résiduelle</th>
                                            <td><?php echo e(number_format($immobilisation->valeur_residuelle ?? 0, 2, ',', ' ')); ?> DH</td>
                                        </tr>
                                        <tr>
                                            <th>VNC actuelle</th>
                                            <td><?php echo e(number_format($immobilisation->vnc, 2, ',', ' ')); ?> DH</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?php if($immobilisation->photo_path): ?>
                            <h6 class="text-uppercase text-sm">Photo</h6>
                            <div class="text-center">
                                <img src="<?php echo e(Storage::url($immobilisation->photo_path)); ?>" alt="Photo de l'immobilisation" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Onglets pour les informations détaillées -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="amortissement-tab" data-bs-toggle="tab" data-bs-target="#amortissement" type="button" role="tab" aria-controls="amortissement" aria-selected="true">Plan d'amortissement</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="mouvements-tab" data-bs-toggle="tab" data-bs-target="#mouvements" type="button" role="tab" aria-controls="mouvements" aria-selected="false">Mouvements</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="maintenances-tab" data-bs-toggle="tab" data-bs-target="#maintenances" type="button" role="tab" aria-controls="maintenances" aria-selected="false">Maintenances</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="contrats-tab" data-bs-toggle="tab" data-bs-target="#contrats" type="button" role="tab" aria-controls="contrats" aria-selected="false">Contrats</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="amortissement" role="tabpanel" aria-labelledby="amortissement-tab">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Année</th>
                                                    <th>Base</th>
                                                    <th>Taux</th>
                                                    <th>Dotation</th>
                                                    <th>Cumul début</th>
                                                    <th>Cumul fin</th>
                                                    <th>VNC fin</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__empty_1 = true; $__currentLoopData = $immobilisation->planAmortissements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><?php echo e($plan->annee_exercice); ?></td>
                                                    <td><?php echo e(number_format($plan->base_amortissable, 2, ',', ' ')); ?> DH</td>
                                                    <td><?php echo e(number_format($plan->taux_applique * 100, 2, ',', ' ')); ?> %</td>
                                                    <td><?php echo e(number_format($plan->dotation_annuelle, 2, ',', ' ')); ?> DH</td>
                                                    <td><?php echo e(number_format($plan->amortissement_cumule_debut, 2, ',', ' ')); ?> DH</td>
                                                    <td><?php echo e(number_format($plan->amortissement_cumule_fin, 2, ',', ' ')); ?> DH</td>
                                                    <td><?php echo e(number_format($plan->vna_fin_exercice, 2, ',', ' ')); ?> DH</td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="7" class="text-center">Aucun plan d'amortissement disponible</td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="mouvements" role="tabpanel" aria-labelledby="mouvements-tab">
                                    <div class="d-flex justify-content-end mt-3">
                                        <a href="<?php echo e(route('mouvements.create', ['immobilisation_id' => $immobilisation->id])); ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-2"></i>Nouveau mouvement
                                        </a>
                                    </div>
                                    <div class="table-responsive mt-2">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Type</th>
                                                    <th>Valeur</th>
                                                    <th>VNC</th>
                                                    <th>Description</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__empty_1 = true; $__currentLoopData = $immobilisation->mouvements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mouvement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><?php echo e($mouvement->date_mouvement ? $mouvement->date_mouvement->format('d/m/Y') : 'N/A'); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo e($mouvement->type_mouvement == 'entree' ? 'success' : ($mouvement->type_mouvement == 'sortie' ? 'danger' : 'info')); ?>">
                                                            <?php echo e($mouvement->type_formatted); ?>

                                                        </span>
                                                    </td>
                                                    <td><?php echo e(number_format($mouvement->valeur_mouvement, 2, ',', ' ')); ?> DH</td>
                                                    <td><?php echo e(number_format($mouvement->valeur_nette_comptable, 2, ',', ' ')); ?> DH</td>
                                                    <td><?php echo e(Str::limit($mouvement->description, 30)); ?></td>
                                                    <td>
                                                        <a href="<?php echo e(route('mouvements.show', $mouvement->id)); ?>" class="btn btn-sm btn-info mx-1" title="Voir">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">Aucun mouvement enregistré</td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="maintenances" role="tabpanel" aria-labelledby="maintenances-tab">
                                    <div class="d-flex justify-content-end mt-3">
                                        <a href="<?php echo e(route('maintenances.create', ['immobilisation_id' => $immobilisation->id])); ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-2"></i>Nouvelle maintenance
                                        </a>
                                    </div>
                                    <div class="table-responsive mt-2">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Type</th>
                                                    <th>Prestataire</th>
                                                    <th>Coût</th>
                                                    <th>Est charge</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__empty_1 = true; $__currentLoopData = $immobilisation->maintenances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $maintenance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><?php echo e($maintenance->date_intervention ? $maintenance->date_intervention->format('d/m/Y') : 'N/A'); ?></td>
                                                    <td><?php echo e($maintenance->type); ?></td>
                                                    <td><?php echo e($maintenance->prestataire->nom ?? 'N/A'); ?></td>
                                                    <td><?php echo e(number_format($maintenance->cout, 2, ',', ' ')); ?> DH</td>
                                                    <td>
                                                        <span class="badge bg-<?php echo e($maintenance->est_charge ? 'primary' : 'warning'); ?>">
                                                            <?php echo e($maintenance->est_charge ? 'Charge' : 'À capitaliser'); ?>

                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo e(route('maintenances.show', $maintenance->id)); ?>" class="btn btn-sm btn-info mx-1" title="Voir">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">Aucune maintenance enregistrée</td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="contrats" role="tabpanel" aria-labelledby="contrats-tab">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Référence</th>
                                                    <th>Type</th>
                                                    <th>Prestataire</th>
                                                    <th>Période</th>
                                                    <th>Montant</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__empty_1 = true; $__currentLoopData = $immobilisation->contrats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contrat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><?php echo e($contrat->reference); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo e($contrat->type == 'maintenance' ? 'info' : ($contrat->type == 'location' ? 'primary' : ($contrat->type == 'leasing' ? 'warning' : 'secondary'))); ?>">
                                                            <?php echo e(ucfirst($contrat->type)); ?>

                                                        </span>
                                                    </td>
                                                    <td><?php echo e($contrat->prestataire->nom ?? 'N/A'); ?></td>
                                                    <td>
                                                        <?php echo e($contrat->date_debut ? $contrat->date_debut->format('d/m/Y') : 'N/A'); ?>

                                                        <?php if($contrat->date_fin): ?>
                                                            au <?php echo e($contrat->date_fin->format('d/m/Y')); ?>

                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo e(number_format($contrat->montant_periodique, 2, ',', ' ')); ?> DH</td>
                                                    <td>
                                                        <a href="<?php echo e(route('contrats.show', $contrat->id)); ?>" class="btn btn-sm btn-info mx-1" title="Voir">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">Aucun contrat associé</td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/immobilisations/show.blade.php ENDPATH**/ ?>