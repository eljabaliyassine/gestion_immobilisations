<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        "dossier_id",
        "site_id",
        "code",
        "libelle",
    ];

    /**
     * Get the dossier that owns the service.
     */
    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class);
    }

    /**
     * Get the site that the service belongs to.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the immobilisations assigned to the service.
     */
    public function immobilisations(): HasMany
    {
        return $this->hasMany(Immobilisation::class);
    }

    /**
     * Get the inventaires conducted for the service.
     */
    public function inventaires(): HasMany
    {
        return $this->hasMany(Inventaire::class);
    }
}
