<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MouvementImmobilisation extends Model
{
    use HasFactory;

    protected $table = 'mouvements_immobilisations';

    protected $fillable = [
        'immobilisation_id',
        'type_mouvement',
        'date_mouvement',
        'valeur_mouvement',
        'valeur_nette_comptable',
        'compte_contrepartie',
        'description',
        'user_id',
    ];

    protected $casts = [
        'date_mouvement' => 'date',
        'valeur_mouvement' => 'decimal:2',
        'valeur_nette_comptable' => 'decimal:2',
    ];

    /**
     * Relation avec l'immobilisation
     */
    public function immobilisation(): BelongsTo
    {
        return $this->belongsTo(Immobilisation::class);
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accesseur pour obtenir le type formaté
     */
    public function getTypeFormattedAttribute()
    {
        $types = [
            'entree' => 'Entrée',
            'sortie' => 'Sortie',
            'transfert' => 'Transfert',
            'cession' => 'Cession',
            'rebut' => 'Mise au rebut',
            'amortissement_exceptionnel' => 'Amortissement exceptionnel',
        ];

        return $types[$this->type_mouvement] ?? ucfirst($this->type_mouvement);
    }

    /**
     * Scope pour filtrer par immobilisation
     */
    public function scopeForImmobilisation($query, $immobilisationId)
    {
        return $query->where('immobilisation_id', $immobilisationId);
    }

    /**
     * Scope pour filtrer par type de mouvement
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type_mouvement', $type);
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopeForPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_mouvement', [$dateDebut, $dateFin]);
    }

    /**
     * Vérifie si ce mouvement appartient au dossier spécifié
     * via la relation avec l'immobilisation
     */
    public function appartientAuDossier($dossierId): bool
    {
        return $this->immobilisation && $this->immobilisation->dossier_id == $dossierId;
    }
}
