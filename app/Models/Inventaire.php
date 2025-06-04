<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventaire extends Model
{
    use HasFactory;

    protected $fillable = [
        "dossier_id",
        "reference",
        "date_debut",
        "date_fin",
        "responsable_id",
        "site_id",
        "service_id",
        "statut",
        "description",
    ];

    protected $casts = [
        "date_debut" => "date",
        "date_fin" => "date",
    ];

    /**
     * Get the dossier that owns the inventaire.
     */
    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class);
    }

    /**
     * Get the user responsible for the inventaire.
     */
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, "responsable_id");
    }

    /**
     * Alias for responsable() to maintain compatibility with controller.
     */
    public function user(): BelongsTo
    {
        return $this->responsable();
    }

    /**
     * Get the site associated with the inventaire (if specific).
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the service associated with the inventaire (if specific).
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the details (scanned items) for the inventaire.
     */
    public function details(): HasMany
    {
        return $this->hasMany(InventaireDetail::class);
    }
}
