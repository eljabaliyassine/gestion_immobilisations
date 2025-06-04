<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Mouvement extends Model
{
    use HasFactory;

    protected $table = "mouvements_immobilisations";

    protected $fillable = [
        "immobilisation_id",
        "type_mouvement",
        "date_mouvement",
        "site_id",
        "service_id",
        "commentaire",
        "user_id"
    ];

    protected $casts = [
        "date_mouvement" => "date",
    ];

    /**
     * Get the immobilisation that owns the mouvement.
     */
    public function immobilisation(): BelongsTo
    {
        return $this->belongsTo(Immobilisation::class);
    }

    /**
     * Get the site associated with the mouvement.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the service associated with the mouvement.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the user who created the mouvement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include mouvements for a specific dossier.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $dossierId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDossier($query, $dossierId)
    {
        return $query->whereHas('immobilisation', function($q) use ($dossierId) {
            $q->where('dossier_id', $dossierId);
        });
    }

    /**
     * Get the formatted type of mouvement.
     *
     * @return string
     */
    public function getTypeFormattedAttribute(): string
    {
        $types = [
            'entree' => 'Entrée',
            'sortie' => 'Sortie',
            'transfert' => 'Transfert'
        ];

        return $types[$this->type_mouvement] ?? $this->type_mouvement;
    }

    /**
     * Get the previous location before this movement.
     *
     * @return array|null
     */
    public function getPreviousLocationAttribute(): ?array
    {
        $previousMouvement = Mouvement::where('immobilisation_id', $this->immobilisation_id)
            ->where('date_mouvement', '<', $this->date_mouvement)
            ->orderBy('date_mouvement', 'desc')
            ->first();

        if (!$previousMouvement) {
            return null;
        }

        return [
            'site' => $previousMouvement->site,
            'service' => $previousMouvement->service
        ];
    }
}
