<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Immobilisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'dossier_id',
        'code_barre',
        'designation',
        'description',
        'famille_id',
        'site_id',
        'service_id',
        'emplacement', // AJOUTÉ
        'date_acquisition',
        'date_mise_service',
        'valeur_acquisition',
        'tva_deductible',
        'comptecompta_tva_id',
        'base_amortissement',
        'statut',
        'fournisseur_id',
        'numero_facture',
        'numero_serie',
        'photo_path',
        'document_path',
        // Champs hérités de la famille
        'comptecompta_immobilisation_id',
        'comptecompta_amortissement_id',
        'comptecompta_dotation_id',
        'duree_amortissement',
        'methode_amortissement',
    ];

    protected $casts = [
        'date_acquisition' => 'date',
        'date_mise_service' => 'date',
        'valeur_acquisition' => 'decimal:2',
        'tva_deductible' => 'decimal:2',
        'base_amortissement' => 'decimal:2',
    ];

    /**
     * Relation avec le dossier
     */
    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class);
    }

    /**
     * Relation avec la famille
     */
    public function famille(): BelongsTo
    {
        return $this->belongsTo(Famille::class);
    }

    /**
     * Relation avec le site
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Relation avec le service
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Relation avec le fournisseur
     */
    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    /**
     * Relation avec le compte comptable d'immobilisation
     */
    public function compteImmobilisation(): BelongsTo
    {
        return $this->belongsTo(CompteCompta::class, 'comptecompta_immobilisation_id');
    }

    /**
     * Relation avec le compte comptable d'amortissement
     */
    public function compteAmortissement(): BelongsTo
    {
        return $this->belongsTo(CompteCompta::class, 'comptecompta_amortissement_id');
    }

    /**
     * Relation avec le compte comptable de dotation
     */
    public function compteDotation(): BelongsTo
    {
        return $this->belongsTo(CompteCompta::class, 'comptecompta_dotation_id');
    }

    /**
     * Relation avec le compte comptable de TVA
     */
    public function compteTva(): BelongsTo
    {
        return $this->belongsTo(CompteCompta::class, 'comptecompta_tva_id');
    }

    /**
     * Relation avec les plans d'amortissement
     */
    public function planAmortissements(): HasMany
    {
        return $this->hasMany(PlanAmortissement::class);
    }

    /**
     * Relation avec les mouvements
     */
    public function mouvements(): HasMany
    {
        return $this->hasMany(Mouvement::class);
    }

    /**
     * Relation avec les maintenances
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    /**
     * Relation avec les contrats
     */
    public function contrats(): HasMany
    {
        return $this->hasMany(Contrat::class);
    }

    /**
     * Scope pour filtrer par dossier
     */
    public function scopeForDossier($query, $dossierId)
    {
        return $query->where('dossier_id', $dossierId);
    }

    /**
     * Scope pour filtrer par statut actif
     */
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Accessor pour le statut affiché
     */
    public function getStatutAfficheAttribute()
    {
        return $this->statut === 'actif' ? 'En service' : $this->statut;
    }

    /**
     * Mutator pour le statut
     */
    public function setStatutAttribute($value)
    {
        $this->attributes['statut'] = $value === 'En service' ? 'actif' : $value;
    }
}