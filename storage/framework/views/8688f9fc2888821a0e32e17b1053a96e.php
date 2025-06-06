<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Nouveau contrat</h5>
                    <a href="<?php echo e(route('contrats.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
                <div class="card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('contrats.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reference" class="form-control-label">Référence <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="reference" name="reference" value="<?php echo e(old('reference')); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="form-control-label">Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="maintenance" <?php echo e(old('type') == 'maintenance' ? 'selected' : ''); ?>>Maintenance</option>
                                        <option value="location" <?php echo e(old('type') == 'location' ? 'selected' : ''); ?>>Location</option>
                                        <option value="leasing" <?php echo e(old('type') == 'leasing' ? 'selected' : ''); ?>>Crédit-bail</option>
                                        <option value="autre" <?php echo e(old('type') == 'autre' ? 'selected' : ''); ?>>Autre</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prestataire_id" class="form-control-label">Prestataire</label>
                                    <select class="form-control" id="prestataire_id" name="prestataire_id">
                                        <option value="">Sélectionner un prestataire</option>
                                        <?php $__currentLoopData = $prestataires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prestataire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($prestataire->id); ?>" <?php echo e(old('prestataire_id') == $prestataire->id ? 'selected' : ''); ?>>
                                                <?php echo e($prestataire->nom); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fournisseur_id" class="form-control-label">Fournisseur</label>
                                    <select class="form-control" id="fournisseur_id" name="fournisseur_id">
                                        <option value="">Sélectionner un fournisseur</option>
                                        <?php $__currentLoopData = $fournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fournisseur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($fournisseur->id); ?>" <?php echo e(old('fournisseur_id') == $fournisseur->id ? 'selected' : ''); ?>>
                                                <?php echo e($fournisseur->nom); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_debut" class="form-control-label">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?php echo e(old('date_debut')); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_fin" class="form-control-label">Date de fin</label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?php echo e(old('date_fin')); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_prochaine_echeance" class="form-control-label">Date prochaine échéance</label>
                                    <input type="date" class="form-control" id="date_prochaine_echeance" name="date_prochaine_echeance" value="<?php echo e(old('date_prochaine_echeance')); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="montant_periodique" class="form-control-label">Montant périodique (DH) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" id="montant_periodique" name="montant_periodique" value="<?php echo e(old('montant_periodique')); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="periodicite" class="form-control-label">Périodicité <span class="text-danger">*</span></label>
                                    <select class="form-control" id="periodicite" name="periodicite" required>
                                        <option value="">Sélectionner une périodicité</option>
                                        <option value="mensuel" <?php echo e(old('periodicite') == 'mensuel' ? 'selected' : ''); ?>>Mensuel</option>
                                        <option value="trimestriel" <?php echo e(old('periodicite') == 'trimestriel' ? 'selected' : ''); ?>>Trimestriel</option>
                                        <option value="semestriel" <?php echo e(old('periodicite') == 'semestriel' ? 'selected' : ''); ?>>Semestriel</option>
                                        <option value="annuel" <?php echo e(old('periodicite') == 'annuel' ? 'selected' : ''); ?>>Annuel</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="statut" class="form-control-label">Statut <span class="text-danger">*</span></label>
                                    <select class="form-control" id="statut" name="statut" required>
                                        <option value="actif" <?php echo e(old('statut') == 'actif' ? 'selected' : ''); ?>>Actif</option>
                                        <option value="inactif" <?php echo e(old('statut') == 'inactif' ? 'selected' : ''); ?>>Inactif</option>
                                        <option value="termine" <?php echo e(old('statut') == 'termine' ? 'selected' : ''); ?>>Terminé</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description" class="form-control-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo e(old('description')); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="document_path" class="form-control-label">Document (PDF, DOC, DOCX)</label>
                            <input type="file" class="form-control" id="document_path" name="document_path">
                        </div>
                        
                        <!-- Champs spécifiques au crédit-bail -->
                        <div id="credit-bail-fields" class="mt-4" style="display: none;">
                            <h6 class="mb-3">Détails du crédit-bail</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="duree_mois" class="form-control-label">Durée (mois) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="duree_mois" name="duree_mois" value="<?php echo e(old('duree_mois')); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="valeur_residuelle" class="form-control-label">Valeur résiduelle (DH) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" class="form-control" id="valeur_residuelle" name="valeur_residuelle" value="<?php echo e(old('valeur_residuelle', 0)); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="taux_interet_periodique" class="form-control-label">Taux d'intérêt périodique (%)</label>
                                        <input type="number" step="0.001" class="form-control" id="taux_interet_periodique" name="taux_interet_periodique" value="<?php echo e(old('taux_interet_periodique')); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Afficher/masquer les champs spécifiques au crédit-bail
        const typeSelect = document.getElementById('type');
        const creditBailFields = document.getElementById('credit-bail-fields');
        
        function toggleCreditBailFields() {
            if (typeSelect.value === 'leasing') {
                creditBailFields.style.display = 'block';
                document.getElementById('duree_mois').setAttribute('required', 'required');
                document.getElementById('valeur_residuelle').setAttribute('required', 'required');
            } else {
                creditBailFields.style.display = 'none';
                document.getElementById('duree_mois').removeAttribute('required');
                document.getElementById('valeur_residuelle').removeAttribute('required');
            }
        }
        
        // Initialiser l'affichage
        toggleCreditBailFields();
        
        // Écouter les changements
        typeSelect.addEventListener('change', toggleCreditBailFields);
        
        // Remplir automatiquement la date de prochaine échéance
        const dateDebutInput = document.getElementById('date_debut');
        const dateProchainEcheanceInput = document.getElementById('date_prochaine_echeance');
        const periodiciteSelect = document.getElementById('periodicite');
        
        function updateDateProchainEcheance() {
            if (dateDebutInput.value) {
                const dateDebut = new Date(dateDebutInput.value);
                let dateProchainEcheance = new Date(dateDebut);
                
                switch (periodiciteSelect.value) {
                    case 'mensuel':
                        dateProchainEcheance.setMonth(dateProchainEcheance.getMonth() + 1);
                        break;
                    case 'trimestriel':
                        dateProchainEcheance.setMonth(dateProchainEcheance.getMonth() + 3);
                        break;
                    case 'semestriel':
                        dateProchainEcheance.setMonth(dateProchainEcheance.getMonth() + 6);
                        break;
                    case 'annuel':
                        dateProchainEcheance.setFullYear(dateProchainEcheance.getFullYear() + 1);
                        break;
                }
                
                // Formater la date au format YYYY-MM-DD
                const year = dateProchainEcheance.getFullYear();
                const month = String(dateProchainEcheance.getMonth() + 1).padStart(2, '0');
                const day = String(dateProchainEcheance.getDate()).padStart(2, '0');
                dateProchainEcheanceInput.value = `${year}-${month}-${day}`;
            }
        }
        
        dateDebutInput.addEventListener('change', updateDateProchainEcheance);
        periodiciteSelect.addEventListener('change', updateDateProchainEcheance);
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/contrats/create.blade.php ENDPATH**/ ?>