<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanAmortissement extends Model
{
    use HasFactory;

    protected $table = 'plans_amortissement';

    protected $fillable = [
        'immobilisation_id',
        'annee_exercice',
        'base_amortissable',
        'taux_applique',
        'dotation_annuelle',
        'amortissement_cumule_debut',
        'amortissement_cumule_fin',
        'vna_fin_exercice',
    ];

    protected $casts = [
        'base_amortissable' => 'decimal:2',
        'taux_applique' => 'decimal:4',
        'dotation_annuelle' => 'decimal:2',
        'amortissement_cumule_debut' => 'decimal:2',
        'amortissement_cumule_fin' => 'decimal:2',
        'vna_fin_exercice' => 'decimal:2',
    ];

    /**
     * Relation avec l'immobilisation
     */
    public function immobilisation(): BelongsTo
    {
        return $this->belongsTo(Immobilisation::class);
    }

    /**
     * Scope pour filtrer par immobilisation
     */
    public function scopeForImmobilisation($query, $immobilisationId)
    {
        return $query->where('immobilisation_id', $immobilisationId);
    }

    /**
     * Scope pour filtrer par année d'exercice
     */
    public function scopeForExercice($query, $annee)
    {
        return $query->where('annee_exercice', $annee);
    }

    /**
     * Accesseur pour formater le taux appliqué en pourcentage
     */
    public function getTauxFormateAttribute()
    {
        return number_format($this->taux_applique * 100, 2, ',', ' ') . ' %';
    }

    /**
     * Accesseur pour formater la dotation annuelle
     */
    public function getDotationFormateAttribute()
    {
        return number_format($this->dotation_annuelle, 2, ',', ' ') . ' DH';
    }

    /**
     * Accesseur pour formater la VNC en fin d'exercice
     */
    public function getVnaFormateAttribute()
    {
        return number_format($this->vna_fin_exercice, 2, ',', ' ') . ' DH';
    }

    /**
     * Vérifie si ce plan d'amortissement appartient au dossier spécifié
     * via la relation avec l'immobilisation
     */
    public function appartientAuDossier($dossierId): bool
    {
        return $this->immobilisation && $this->immobilisation->dossier_id == $dossierId;
    }
}
