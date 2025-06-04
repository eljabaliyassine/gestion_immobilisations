<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Plan d'amortissement</h5>
                </div>
                <div class="card-body">
                    <?php if(session('success') || isset($success)): ?>
                        <div class="alert alert-success mx-4 mt-3">
                            <?php echo e(session('success') ?? $success ?? ''); ?>

                        </div>
                    <?php endif; ?>
                    
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger mx-4 mt-3">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('amortissements.generer')); ?>" method="POST" class="mb-4">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_derniere_cloture">Date dernière clôture</label>
                                    <input type="date" class="form-control" id="date_derniere_cloture" name="date_derniere_cloture" value="<?php echo e($dateDerniereCloture ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_prochaine_cloture">Date prochaine clôture</label>
                                    <input type="date" class="form-control" id="date_prochaine_cloture" name="date_prochaine_cloture" value="<?php echo e($dateProchaineCloture ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sync-alt me-2"></i>Générer plan d'amortissement
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <?php if(isset($plansAmortissement) && $plansAmortissement->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Immobilisation</th>
                                    <th>Famille</th>
                                    <th>Date mise en service</th>
                                    <th>Base amortissable</th>
                                    <th>Taux</th>
                                    <th>Amort. cumulé début</th>
                                    <th>Dotation période</th>
                                    <th>Amort. cumulé fin</th>
                                    <th>VNA fin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $plansAmortissement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($plan->immobilisations_description); ?></td>
                                    <td><?php echo e($plan->famille); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($plan->immobilisation_date_mise_service)->format('d/m/Y')); ?></td>
                                    <td><?php echo e(number_format($plan->base_amortissable, 2, ',', ' ')); ?> DH</td>
                                    <td><?php echo e(number_format($plan->taux_applique * 100, 2, ',', ' ')); ?> %</td>
                                    <td><?php echo e(number_format($plan->amortissement_cumule_debut, 2, ',', ' ')); ?> DH</td>
                                    <td><?php echo e(number_format($plan->dotation_periode, 2, ',', ' ')); ?> DH</td>
                                    <td><?php echo e(number_format($plan->amortissement_cumule_fin, 2, ',', ' ')); ?> DH</td>
                                    <td><?php echo e(number_format($plan->vna_fin_exercice, 2, ',', ' ')); ?> DH</td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
			<a href="<?php echo e(route('amortissements.export.csv', ['date_derniere_cloture' => $dateDerniereCloture, 'date_prochaine_cloture' => $dateProchaineCloture])); ?>" class="btn btn-info">
			    <i class="fas fa-file-csv me-2"></i>Exporter CSV (tous les champs)
			</a>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info">
                        <?php if(isset($success)): ?>
                            Il semble qu'aucune immobilisation active n'ait été trouvée pour les dates sélectionnées. 
                            Veuillez vérifier que des immobilisations actives existent dans le dossier courant.
                        <?php else: ?>
                            Veuillez générer le plan d'amortissement en sélectionnant les dates ci-dessus.
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/amortissements/plan.blade.php ENDPATH**/ ?>