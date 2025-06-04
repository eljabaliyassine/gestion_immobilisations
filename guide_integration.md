# Guide d'intégration - Plan d'amortissement

Ce document explique les modifications apportées pour intégrer la fonctionnalité de génération et d'export du plan d'amortissement dans votre application Laravel.

## Fichiers modifiés

1. **AmortissementController.php**
   - Ajout des méthodes pour la génération du plan d'amortissement
   - Intégration de l'export Excel

2. **web.php**
   - Ajout des routes pour le plan d'amortissement et l'export

3. **plan.blade.php** (nouveau fichier)
   - Vue pour la sélection des dates et l'affichage du plan d'amortissement

4. **PlanAmortissementExport.php** (nouveau fichier)
   - Classe pour l'export Excel du plan d'amortissement

## Instructions d'installation

1. **Copier les fichiers modifiés**
   - Copiez `AmortissementController.php` dans `app/Http/Controllers/`
   - Copiez `web.php` dans `routes/`
   - Copiez `plan.blade.php` dans `resources/views/amortissements/`
   - Copiez `PlanAmortissementExport.php` dans `app/Exports/`

2. **Installer la dépendance Excel** (si ce n'est pas déjà fait)
   ```bash
   composer require maatwebsite/excel
   ```

3. **Créer le modèle PlanAmortissement** (si ce n'est pas déjà fait)
   ```php
   <?php

   namespace App\Models;

   use Illuminate\Database\Eloquent\Factories\HasFactory;
   use Illuminate\Database\Eloquent\Model;

   class PlanAmortissement extends Model
   {
       use HasFactory;
       
       protected $table = 'plans_amortissement';
       
       protected $fillable = [
           'dossier_id',
           'immobilisation_id',
           'immobilisations_libelle',
           'immobilisations_description',
           'immobilisations_famille_id',
           'famille',
           'immobilisation_date_acquisition',
           'immobilisation_date_mise_service',
           'annee_exercice',
           'base_amortissable',
           'taux_applique',
           'date_derniere_cloture',
           'date_prochaine_cloture',
           'duree_periode',
           'dotation_periode',
           'duree_amortissement_par_defaut',
           'amortissement_cumule_debut',
           'amortissement_cumule_fin',
           'vna_fin_exercice',
           'dotation_annuelle',
       ];
       
       protected $casts = [
           'immobilisation_date_acquisition' => 'date',
           'immobilisation_date_mise_service' => 'date',
           'date_derniere_cloture' => 'date',
           'date_prochaine_cloture' => 'date',
           'base_amortissable' => 'decimal:2',
           'taux_applique' => 'decimal:4',
           'dotation_periode' => 'decimal:2',
           'amortissement_cumule_debut' => 'decimal:2',
           'amortissement_cumule_fin' => 'decimal:2',
           'vna_fin_exercice' => 'decimal:2',
           'dotation_annuelle' => 'decimal:2',
       ];
       
       public function immobilisation()
       {
           return $this->belongsTo(Immobilisation::class);
       }
   }
   ```

4. **Vider les caches de l'application**
   ```bash
   php artisan cache:clear
   php artisan view:clear
   php artisan route:clear
   php artisan config:clear
   ```

## Utilisation

1. Accédez à la page du plan d'amortissement via `/plan-amortissement` ou en utilisant le menu de navigation.
2. Sélectionnez les dates de clôture (dernière et prochaine).
3. Cliquez sur "Générer plan d'amortissement" pour calculer et afficher le plan.
4. Utilisez le bouton "Exporter Excel" pour télécharger les données au format Excel.

## Détails techniques

- La génération du plan d'amortissement utilise les formules spécifiées pour calculer les dotations, amortissements cumulés et VNA.
- Seules les immobilisations actives du dossier courant sont incluses dans le plan.
- Les dates de clôture sont stockées en session pour faciliter la réutilisation.
- L'export Excel inclut un formatage professionnel (en-têtes en couleur, formats de nombres et de dates).

## Dépannage

- Si vous rencontrez des erreurs liées à la classe d'export Excel, assurez-vous que le package `maatwebsite/excel` est bien installé.
- Si les calculs semblent incorrects, vérifiez que les champs `duree_amortissement_par_defaut` dans la table `familles` sont correctement renseignés.
- Pour tout problème d'affichage, vérifiez que les fichiers CSS et JavaScript sont bien chargés dans la vue.
