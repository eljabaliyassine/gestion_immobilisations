<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        "dossier_id",
        "code",
	'libelle',
        "adresse",
    ];

    /**
     * Get the dossier that owns the site.
     */
    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class);
    }

    /**
     * Get the services for the site.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Get the immobilisations located at the site.
     */
    public function immobilisations(): HasMany
    {
        return $this->hasMany(Immobilisation::class);
    }

    /**
     * Get the inventaires conducted at the site.
     */
    public function inventaires(): HasMany
    {
        return $this->hasMany(Inventaire::class);
    }
}
