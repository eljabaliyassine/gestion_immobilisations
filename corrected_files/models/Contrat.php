<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contrat extends Model
{
    use HasFactory;

    protected $table = 'contrats';

    protected $fillable = [
        'dossier_id',
        'reference',
        'type',
        'prestataire_id',
        'fournisseur_id',
        'date_debut',
        'date_fin',
        'description',
        'montant_periodique',
        'periodicite',
        'date_prochaine_echeance',
        'statut',
        'document_path',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_prochaine_echeance' => 'date',
        'montant_periodique' => 'decimal:2',
    ];

    /**
     * Relation avec le dossier
     */
    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class);
    }

    /**
     * Relation avec le prestataire
     */
    public function prestataire(): BelongsTo
    {
        return $this->belongsTo(Prestataire::class);
    }

    /**
     * Relation avec le fournisseur
     */
    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    /**
     * Relation avec les immobilisations
     */
    public function immobilisations()
    {
        return $this->belongsToMany(Immobilisation::class, 'contrat_immobilisation')
                    ->withPivot('date_liaison', 'date_deliaison')
                    ->withTimestamps();
    }

    /**
     * Relation avec les détails de crédit-bail
     */
    public function detailCreditBail(): HasMany
    {
        return $this->hasMany(DetailCreditBail::class);
    }

    /**
     * Relation avec les échéances de crédit-bail
     */
    public function echeancesCreditBail(): HasMany
    {
        return $this->hasMany(EcheanceCreditBail::class, 'detail_credit_bail_contrat_id', 'id');
    }

    /**
     * Scope pour filtrer par dossier
     */
    public function scopeForDossier($query, $dossierId)
    {
        return $query->where('dossier_id', $dossierId);
    }

    /**
     * Scope pour filtrer les contrats actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour filtrer par type de contrat
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Accesseur pour formater le montant périodique
     */
    public function getMontantFormateAttribute()
    {
        return number_format($this->montant_periodique, 2, ',', ' ') . ' DH';
    }

    /**
     * Accesseur pour obtenir la durée du contrat en mois
     */
    public function getDureeEnMoisAttribute()
    {
        if (!$this->date_debut || !$this->date_fin) {
            return null;
        }

        return $this->date_debut->diffInMonths($this->date_fin);
    }

    /**
     * Accesseur pour vérifier si le contrat est expiré
     */
    public function getIsExpiredAttribute()
    {
        if (!$this->date_fin) {
            return false;
        }

        return $this->date_fin->isPast();
    }

    /**
     * Accesseur pour obtenir le nombre de jours restants avant expiration
     */
    public function getJoursRestantsAttribute()
    {
        if (!$this->date_fin || $this->is_expired) {
            return 0;
        }

        return now()->diffInDays($this->date_fin, false);
    }
}
