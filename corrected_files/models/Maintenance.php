<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    use HasFactory;

    protected $table = 'maintenances';

    protected $fillable = [
        'immobilisation_id',
        'date_intervention',
        'type',
        'description',
        'prestataire_id',
        'cout',
        'est_charge',
        'date_fin_intervention',
        'user_id',
    ];

    protected $casts = [
        'date_intervention' => 'date',
        'date_fin_intervention' => 'date',
        'cout' => 'decimal:2',
        'est_charge' => 'boolean',
    ];

    /**
     * Relation avec l'immobilisation
     */
    public function immobilisation(): BelongsTo
    {
        return $this->belongsTo(Immobilisation::class);
    }

    /**
     * Relation avec le prestataire
     */
    public function prestataire(): BelongsTo
    {
        return $this->belongsTo(Prestataire::class);
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour filtrer par immobilisation
     */
    public function scopeForImmobilisation($query, $immobilisationId)
    {
        return $query->where('immobilisation_id', $immobilisationId);
    }

    /**
     * Scope pour filtrer par type de maintenance
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope pour filtrer par prestataire
     */
    public function scopeForPrestataire($query, $prestataireId)
    {
        return $query->where('prestataire_id', $prestataireId);
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopeForPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_intervention', [$dateDebut, $dateFin]);
    }

    /**
     * Accesseur pour formater le coût
     */
    public function getCoutFormateAttribute()
    {
        return number_format($this->cout, 2, ',', ' ') . ' DH';
    }

    /**
     * Accesseur pour obtenir le statut de charge
     */
    public function getEstChargeTexteAttribute()
    {
        return $this->est_charge ? 'Charge' : 'À capitaliser';
    }

    /**
     * Vérifie si cette maintenance appartient au dossier spécifié
     * via la relation avec l'immobilisation
     */
    public function appartientAuDossier($dossierId): bool
    {
        return $this->immobilisation && $this->immobilisation->dossier_id == $dossierId;
    }
}
