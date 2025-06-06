<?php $__env->startSection("title", "Modifier Immobilisation : " . $immobilisation->designation); ?>

<?php $__env->startSection("content"); ?>
<div class="container">
    <h1>Modifier l'Immobilisation : <?php echo e($immobilisation->designation); ?></h1>

    <form action="<?php echo e(route("immobilisations.update", $immobilisation)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field("PUT"); ?>

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
unset($__errorArgs, $__bag); ?>" id="code_barre" name="code_barre" value="<?php echo e(old("code_barre", $immobilisation->code_barre)); ?>">
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
unset($__errorArgs, $__bag); ?>" id="designation" name="designation" value="<?php echo e(old("designation", $immobilisation->designation)); ?>" required autofocus>
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
unset($__errorArgs, $__bag); ?>" id="description" name="description" rows="3"><?php echo e(old("description", $immobilisation->description)); ?></textarea>
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
                                <option value="<?php echo e($famille->id); ?>" <?php echo e(old("famille_id", $immobilisation->famille_id) == $famille->id ? "selected" : ""); ?>><?php echo e($famille->libelle); ?></option>
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
                            <option value="En service" <?php echo e(old("statut", $immobilisation->statut) == "En service" || old("statut", $immobilisation->statut) == "actif" ? "selected" : ""); ?>>En service</option>
                            <option value="En stock" <?php echo e(old("statut", $immobilisation->statut) == "En stock" ? "selected" : ""); ?>>En stock</option>
                            <option value="En réparation" <?php echo e(old("statut", $immobilisation->statut) == "En réparation" ? "selected" : ""); ?>>En réparation</option>
                            <option value="Cédé" <?php echo e(old("statut", $immobilisation->statut) == "Cédé" ? "selected" : ""); ?>>Cédé</option>
                            <option value="Rebut" <?php echo e(old("statut", $immobilisation->statut) == "Rebut" ? "selected" : ""); ?>>Rebut</option>
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
                        <?php if($immobilisation->photo_path): ?>
                            <div class="mt-2">
                                <img src="<?php echo e(Storage::url($immobilisation->photo_path)); ?>" alt="Photo actuelle" height="100">
                                <small class="d-block">Photo actuelle. Télécharger un nouveau fichier pour remplacer.</small>
                            </div>
                        <?php endif; ?>
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
unset($__errorArgs, $__bag); ?>" id="date_acquisition" name="date_acquisition" value="<?php echo e(old("date_acquisition", $immobilisation->date_acquisition ? $immobilisation->date_acquisition->format("Y-m-d") : null)); ?>" required>
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
unset($__errorArgs, $__bag); ?>" id="date_mise_service" name="date_mise_service" value="<?php echo e(old("date_mise_service", $immobilisation->date_mise_service ? $immobilisation->date_mise_service->format("Y-m-d") : null)); ?>">
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
unset($__errorArgs, $__bag); ?>" id="valeur_acquisition" name="valeur_acquisition" value="<?php echo e(old("valeur_acquisition", $immobilisation->valeur_acquisition)); ?>" required>
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
                                <option value="<?php echo e($fournisseur->id); ?>" <?php echo e(old("fournisseur_id", $immobilisation->fournisseur_id) == $fournisseur->id ? "selected" : ""); ?>><?php echo e($fournisseur->nom); ?></option>
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
unset($__errorArgs, $__bag); ?>" id="numero_facture" name="numero_facture" value="<?php echo e(old("numero_facture", $immobilisation->numero_facture)); ?>">
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
                                <option value="<?php echo e($site->id); ?>" <?php echo e(old("site_id", $immobilisation->site_id) == $site->id ? "selected" : ""); ?>><?php echo e($site->libelle); ?></option>
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
                            <option value="">-- Sélectionner --</option>
                            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($service->id); ?>" <?php echo e(old("service_id", $immobilisation->service_id) == $service->id ? "selected" : ""); ?>><?php echo e($service->libelle); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
unset($__errorArgs, $__bag); ?>" id="emplacement" name="emplacement" value="<?php echo e(old("emplacement", $immobilisation->emplacement)); ?>">
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
                        <input type="text" class="form-control" id="comptecompta_immobilisation_display" value="<?php echo e($immobilisation->compteImmobilisation ? $immobilisation->compteImmobilisation->numero . ' - ' . $immobilisation->compteImmobilisation->libelle : 'Non défini'); ?>" readonly disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Compte Amortissement</label>
                        <input type="text" class="form-control" id="comptecompta_amortissement_display" value="<?php echo e($immobilisation->compteAmortissement ? $immobilisation->compteAmortissement->numero . ' - ' . $immobilisation->compteAmortissement->libelle : 'Non défini'); ?>" readonly disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Compte Dotation</label>
                        <input type="text" class="form-control" id="comptecompta_dotation_display" value="<?php echo e($immobilisation->compteDotation ? $immobilisation->compteDotation->numero . ' - ' . $immobilisation->compteDotation->libelle : 'Non défini'); ?>" readonly disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Durée Amortissement</label>
                        <input type="text" class="form-control" id="duree_amortissement_display" value="<?php echo e($immobilisation->duree_amortissement ? $immobilisation->duree_amortissement . ' ans' : 'Non défini'); ?>" readonly disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Méthode Amortissement</label>
                        <input type="text" class="form-control" id="methode_amortissement_display" value="<?php echo e($immobilisation->methode_amortissement === 'lineaire' ? 'Linéaire' : ($immobilisation->methode_amortissement === 'degressif' ? 'Dégressif' : 'Non défini')); ?>" readonly disabled>
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
unset($__errorArgs, $__bag); ?>" id="base_amortissement" name="base_amortissement" value="<?php echo e(old("base_amortissement", $immobilisation->base_amortissement)); ?>" required>
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
                                <option value="<?php echo e($compte->id); ?>" <?php echo e(old("comptecompta_tva_id", $immobilisation->comptecompta_tva_id) == $compte->id ? "selected" : ""); ?>><?php echo e($compte->numero); ?> - <?php echo e($compte->libelle); ?></option>
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

        <div class="row mb-0">
            <div class="col-md-8 offset-md-2 text-center">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="<?php echo e(route("immobilisations.index")); ?>" class="btn btn-secondary">Annuler</a>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush("scripts"); ?>
<script>
    // Gestion de la date de mise en service auto-remplie
    document.getElementById("date_acquisition").addEventListener("change", function() {
        const dateMiseService = document.getElementById("date_mise_service");
        if (!dateMiseService.value) {
            dateMiseService.value = this.value;
        }
    });

    const siteSelect = document.getElementById("site_id");
    const serviceSelect = document.getElementById("service_id");
    const initialSiteId = "<?php echo e(old("site_id", $immobilisation->site_id)); ?>";
    const initialServiceId = "<?php echo e(old("service_id", $immobilisation->service_id)); ?>";
    const dossierId = <?php echo e(session("dossier_id")); ?>;

    function fetchServices(siteId) {
        serviceSelect.innerHTML = "<option value=''>-- Chargement... --</option>";
        if (!siteId) {
            loadAllServices();
            return;
        }

        fetch(`/api/dossiers/${dossierId}/sites/${siteId}/services`)
            .then(response => response.json())
            .then(data => {
                serviceSelect.innerHTML = "<option value=''>-- Sélectionner --</option>";
                data.forEach(service => {
                    const option = document.createElement("option");
                    option.value = service.id;
                    option.textContent = service.libelle;
                    // Select the initial service if it matches
                    if (service.id == initialServiceId && siteId == initialSiteId) {
                        option.selected = true;
                    }
                    serviceSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error("Error fetching services:", error);
                serviceSelect.innerHTML = "<option value=''>Erreur chargement</option>";
                loadAllServices(); // Fallback to loading all services
            });
    }

    // Fonction pour charger tous les services du dossier courant
    function loadAllServices() {
        const serviceSelect = document.getElementById("service_id");
        
        fetch(`/api/dossiers/${dossierId}/services`)
            .then(response => response.json())
            .then(data => {
                serviceSelect.innerHTML = "<option value=''>-- Sélectionner --</option>";
                data.forEach(service => {
                    const option = document.createElement("option");
                    option.value = service.id;
                    option.textContent = service.libelle;
                    if (service.id == initialServiceId) {
                        option.selected = true;
                    }
                    serviceSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error("Error fetching all services:", error);
                serviceSelect.innerHTML = "<option value=''>Erreur chargement</option>";
            });
    }

    siteSelect.addEventListener("change", function() {
        fetchServices(this.value);
    });

    // Gestion de l'affichage des informations héritées de la famille
    document.getElementById("famille_id").addEventListener("change", function() {
        const familleId = this.value;
        
        if (!familleId) {
            return;
        }

        // Récupérer les informations de la famille sélectionnée
        fetch(`/api/familles/${familleId}/info`)
            .then(response => response.json())
            .then(data => {
                // Afficher les informations dans les champs en lecture seule
                document.getElementById("comptecompta_immobilisation_display").value = data.comptecompta_immobilisation || "Non défini";
                document.getElementById("comptecompta_amortissement_display").value = data.comptecompta_amortissement || "Non défini";
                document.getElementById("comptecompta_dotation_display").value = data.comptecompta_dotation || "Non défini";
                document.getElementById("duree_amortissement_display").value = data.duree_amortissement ? data.duree_amortissement + " ans" : "Non défini";
                document.getElementById("methode_amortissement_display").value = data.methode_amortissement === "lineaire" ? "Linéaire" : 
                                                                                (data.methode_amortissement === "degressif" ? "Dégressif" : "Non défini");
            })
            .catch(error => {
                console.error("Error fetching famille info:", error);
            });
    });

    // Initial load of services
    document.addEventListener("DOMContentLoaded", function() {
        // Charger tous les services du dossier courant par défaut
        loadAllServices();
        
        const familleSelect = document.getElementById("famille_id");
        if (familleSelect.value) {
            familleSelect.dispatchEvent(new Event("change"));
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\gestion_immobilisations\resources\views/immobilisations/edit.blade.php ENDPATH**/ ?>