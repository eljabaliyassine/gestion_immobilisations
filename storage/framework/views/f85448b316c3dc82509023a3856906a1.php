<?php $__env->startSection("title", "Nouvelle Immobilisation"); ?>

<?php $__env->startSection("content"); ?>
<div class="container">
    <h1>Créer une Nouvelle Immobilisation</h1>

    <form action="<?php echo e(route("immobilisations.store")); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="card mb-3">
            <div class="card-header">Informations Générales</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="code_barre" class="form-label">Code Barre</label>
                        <input type="text" class="form-control <?php $__errorArgs = ["code_barre"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="code_barre" name="code_barre" value="<?php echo e(old("code_barre")); ?>">
                        <?php $__errorArgs = ["code_barre"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label for="designation" class="form-label">Désignation *</label>
                        <input type="text" class="form-control <?php $__errorArgs = ["designation"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="designation" name="designation" value="<?php echo e(old("designation")); ?>" required autofocus>
                        <?php $__errorArgs = ["designation"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control <?php $__errorArgs = ["description"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" name="description" rows="3"><?php echo e(old("description")); ?></textarea>
                        <?php $__errorArgs = ["description"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label for="famille_id" class="form-label">Famille *</label>
                        <select class="form-select <?php $__errorArgs = ["famille_id"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="famille_id" name="famille_id" required>
                            <option value="">-- Sélectionner --</option>
                            <?php $__currentLoopData = $familles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $famille): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($famille->id); ?>" <?php echo e(old("famille_id") == $famille->id ? "selected" : ""); ?>><?php echo e($famille->libelle); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ["famille_id"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label for="statut" class="form-label">Statut *</label>
                        <select class="form-select <?php $__errorArgs = ["statut"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="statut" name="statut" required>
                            <option value="En service" <?php echo e(old("statut", "En service") == "En service" ? "selected" : ""); ?>>En service</option>
                            <option value="En stock" <?php echo e(old("statut") == "En stock" ? "selected" : ""); ?>>En stock</option>
                            <option value="En réparation" <?php echo e(old("statut") == "En réparation" ? "selected" : ""); ?>>En réparation</option>
                            <option value="Cédé" <?php echo e(old("statut") == "Cédé" ? "selected" : ""); ?>>Cédé</option>
                            <option value="Rebut" <?php echo e(old("statut") == "Rebut" ? "selected" : ""); ?>>Rebut</option>
                        </select>
                        <?php $__errorArgs = ["statut"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label for="numero_serie" class="form-label">Numéro de Série</label>
                        <input type="text" class="form-control <?php $__errorArgs = ["numero_serie"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="numero_serie" name="numero_serie" value="<?php echo e(old("numero_serie")); ?>">
                        <?php $__errorArgs = ["numero_serie"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" class="form-control <?php $__errorArgs = ["photo"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="photo" name="photo">
                        <?php $__errorArgs = ["photo"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Acquisition & Valeur</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="date_acquisition" class="form-label">Date Acquisition *</label>
                        <input type="date" class="form-control <?php $__errorArgs = ["date_acquisition"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="date_acquisition" name="date_acquisition" value="<?php echo e(old("date_acquisition")); ?>" required>
                        <?php $__errorArgs = ["date_acquisition"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label for="date_mise_service" class="form-label">Date Mise en Service</label>
                        <input type="date" class="form-control <?php $__errorArgs = ["date_mise_service"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="date_mise_service" name="date_mise_service" value="<?php echo e(old("date_mise_service")); ?>">
                        <?php $__errorArgs = ["date_mise_service"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label for="valeur_acquisition" class="form-label">Valeur Acquisition (HT) *</label>
                        <input type="number" step="0.01" class="form-control <?php $__errorArgs = ["valeur_acquisition"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="valeur_acquisition" name="valeur_acquisition" value="<?php echo e(old("valeur_acquisition")); ?>" required>
                        <?php $__errorArgs = ["valeur_acquisition"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label for="tva_deductible" class="form-label">TVA Déductible</label>
                        <input type="number" step="0.01" class="form-control <?php $__errorArgs = ["tva_deductible"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="tva_deductible" name="tva_deductible" value="<?php echo e(old("tva_deductible")); ?>">
                        <?php $__errorArgs = ["tva_deductible"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label for="fournisseur_id" class="form-label">Fournisseur</label>
                        <select class="form-select <?php $__errorArgs = ["fournisseur_id"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="fournisseur_id" name="fournisseur_id">
                            <option value="">-- Sélectionner --</option>
                            <?php $__currentLoopData = $fournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fournisseur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($fournisseur->id); ?>" <?php echo e(old("fournisseur_id") == $fournisseur->id ? "selected" : ""); ?>><?php echo e($fournisseur->nom); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ["fournisseur_id"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label for="numero_facture" class="form-label">Numéro Facture</label>
                        <input type="text" class="form-control <?php $__errorArgs = ["numero_facture"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="numero_facture" name="numero_facture" value="<?php echo e(old("numero_facture")); ?>">
                        <?php $__errorArgs = ["numero_facture"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Localisation</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="site_id" class="form-label">Site *</label>
                        <select class="form-select <?php $__errorArgs = ["site_id"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="site_id" name="site_id" required>
                            <option value="">-- Sélectionner --</option>
                            <?php $__currentLoopData = $sites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $site): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($site->id); ?>" <?php echo e(old("site_id") == $site->id ? "selected" : ""); ?>><?php echo e($site->libelle); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ["site_id"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label for="service_id" class="form-label">Service *</label>
                        <select class="form-select <?php $__errorArgs = ["service_id"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="service_id" name="service_id" required>
                            <option value="">-- Sélectionner d'abord un site --</option>
                        </select>
                        <?php $__errorArgs = ["service_id"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-12">
                        <label for="emplacement" class="form-label">Emplacement Précis</label>
                        <input type="text" class="form-control <?php $__errorArgs = ["emplacement"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="emplacement" name="emplacement" value="<?php echo e(old("emplacement")); ?>">
                        <?php $__errorArgs = ["emplacement"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Amortissement (Informations héritées de la famille)</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Compte Immobilisation</label>
                        <input type="text" class="form-control" id="comptecompta_immobilisation_display" readonly disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Compte Amortissement</label>
                        <input type="text" class="form-control" id="comptecompta_amortissement_display" readonly disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Compte Dotation</label>
                        <input type="text" class="form-control" id="comptecompta_dotation_display" readonly disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Durée Amortissement</label>
                        <input type="text" class="form-control" id="duree_amortissement_display" readonly disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Méthode Amortissement</label>
                        <input type="text" class="form-control" id="methode_amortissement_display" readonly disabled>
                    </div>
                    <div class="col-md-6">
                        <label for="base_amortissement" class="form-label">Base Amortissement *</label>
                        <input type="number" step="0.01" class="form-control <?php $__errorArgs = ["base_amortissement"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="base_amortissement" name="base_amortissement" value="<?php echo e(old("base_amortissement")); ?>" required>
                        <?php $__errorArgs = ["base_amortissement"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label for="comptecompta_tva_id" class="form-label">Compte TVA</label>
                        <select class="form-select <?php $__errorArgs = ["comptecompta_tva_id"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="comptecompta_tva_id" name="comptecompta_tva_id">
                            <option value="">-- Sélectionner --</option>
                            <?php $__currentLoopData = $comptescompta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $compte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($compte->id); ?>" <?php echo e(old("comptecompta_tva_id") == $compte->id ? "selected" : ""); ?>><?php echo e($compte->numero); ?> - <?php echo e($compte->libelle); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ["comptecompta_tva_id"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Documents</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="document_path" class="form-label">Document (PDF, DOC, DOCX)</label>
                        <input type="file" class="form-control <?php $__errorArgs = ["document_path"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="document_path" name="document_path">
                        <?php $__errorArgs = ["document_path"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-8 offset-md-2 text-center">
                <button type="submit" class="btn btn-primary">Créer Immobilisation</button>
                <a href="<?php echo e(route("immobilisations.index")); ?>" class="btn btn-secondary">Annuler</a>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush("scripts"); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Token CSRF pour les requêtes AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                     document.querySelector('input[name="_token"]')?.value;

    console.log('CSRF Token:', csrfToken ? 'Trouvé' : 'Manquant'); // Debug

    // AUTO-REMPLISSAGE: Date de mise en service = Date d'acquisition
    document.getElementById("date_acquisition").addEventListener("change", function() {
        const dateMiseService = document.getElementById("date_mise_service");
        if (!dateMiseService.value) {
            dateMiseService.value = this.value;
        }
    });

    // AUTO-REMPLISSAGE: Base Amortissement = Valeur Acquisition (HT)
    document.getElementById("valeur_acquisition").addEventListener("input", function() {
        const baseAmortissement = document.getElementById("base_amortissement");
        if (this.value) {
            baseAmortissement.value = this.value;
        }
    });

    // FILTRAGE DYNAMIQUE: Services par site sélectionné - SOLUTION ALTERNATIVE
    document.getElementById("site_id").addEventListener("change", function() {
        const siteId = this.value;
        const serviceSelect = document.getElementById("service_id");

        console.log('Site sélectionné:', siteId); // Debug

        // Réinitialiser le select des services
        serviceSelect.innerHTML = "<option value=''>-- Chargement... --</option>";
        serviceSelect.disabled = true;

        if (!siteId) {
            serviceSelect.innerHTML = "<option value=''>-- Sélectionner d'abord un site --</option>";
            serviceSelect.disabled = false;
            return;
        }

        // SOLUTION ALTERNATIVE: Utilise une route POST simple
        const formData = new FormData();
        formData.append('site_id', siteId);
        formData.append('_token', csrfToken);

        fetch('<?php echo e(route("immobilisations.ajax.services-by-site")); ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Réponse services:', response.status); // Debug
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Services reçus:', data); // Debug
            serviceSelect.innerHTML = "<option value=''>-- Sélectionner --</option>";

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(service => {
                    const option = document.createElement("option");
                    option.value = service.id;
                    option.textContent = service.libelle;
                    // Restaurer la sélection si elle existe (old input)
                    if ("<?php echo e(old('service_id')); ?>" == service.id) {
                        option.selected = true;
                    }
                    serviceSelect.appendChild(option);
                });
            } else {
                serviceSelect.innerHTML += "<option value='' disabled>Aucun service trouvé</option>";
            }
            serviceSelect.disabled = false;
        })
        .catch(error => {
            console.error("Erreur lors du chargement des services:", error);
            serviceSelect.innerHTML = "<option value=''>Erreur de chargement</option>";
            serviceSelect.disabled = false;
        });
    });

    // AFFICHAGE DYNAMIQUE: Informations héritées de la famille - SOLUTION ALTERNATIVE
    document.getElementById("famille_id").addEventListener("change", function() {
        const familleId = this.value;

        console.log('Famille sélectionnée:', familleId); // Debug

        // Réinitialiser les champs d'affichage
        const fields = [
            'comptecompta_immobilisation_display',
            'comptecompta_amortissement_display',
            'comptecompta_dotation_display',
            'duree_amortissement_display',
            'methode_amortissement_display'
        ];

        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.value = "";
            }
        });

        if (!familleId) {
            return;
        }

        // SOLUTION ALTERNATIVE: Utilise une route POST simple
        const formData = new FormData();
        formData.append('famille_id', familleId);
        formData.append('_token', csrfToken);

        fetch('<?php echo e(route("immobilisations.ajax.famille-info")); ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Réponse famille:', response.status); // Debug
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Infos famille reçues:', data); // Debug

            // Remplir les champs avec les données reçues
            const fieldMapping = {
                'comptecompta_immobilisation_display': data.comptecompta_immobilisation,
                'comptecompta_amortissement_display': data.comptecompta_amortissement,
                'comptecompta_dotation_display': data.comptecompta_dotation,
                'duree_amortissement_display': data.duree_amortissement_par_defaut,
                'methode_amortissement_display': data.methode_amortissement_par_defaut
            };

            Object.entries(fieldMapping).forEach(([fieldId, value]) => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.value = value || '';
                }
            });
        })
        .catch(error => {
            console.error("Erreur lors du chargement des informations de la famille:", error);
        });
    });

    // Restaurer la sélection des services si un site était déjà sélectionné (old input)
    const oldSiteId = "<?php echo e(old('site_id')); ?>";
    if (oldSiteId) {
        console.log('Restauration site:', oldSiteId); // Debug
        setTimeout(() => {
            document.getElementById("site_id").dispatchEvent(new Event('change'));
        }, 100);
    }

    // Restaurer l'affichage des informations de famille si une famille était déjà sélectionnée
    const oldFamilleId = "<?php echo e(old('famille_id')); ?>";
    if (oldFamilleId) {
        console.log('Restauration famille:', oldFamilleId); // Debug
        setTimeout(() => {
            document.getElementById("famille_id").dispatchEvent(new Event('change'));
        }, 100);
    }
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/immobilisations/create.blade.php ENDPATH**/ ?>