<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prestataire extends Model
{
    use HasFactory;

    protected $fillable = [
        "dossier_id",
	"code",
        "nom",
        "adresse",
        "telephone",
        "email",
    ];

    /**
     * Get the dossier that owns the prestataire.
     */
    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class);
    }

    /**
     * Get the maintenances performed by this prestataire.
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    /**
     * Get the contrats (location/lease-back) associated with this prestataire.
     */
    public function contratsLocation(): HasMany
    {
        return $this->hasMany(Contrat::class, "prestataire_id");
    }
}
