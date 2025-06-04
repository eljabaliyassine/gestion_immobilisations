<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compte extends Model
{
    use HasFactory;

    protected $fillable = [
        "nom",
        "identifiant_unique",
        "adresse",
        "telephone",
        "email",
        "actif",
    ];

    /**
     * Get the dossiers for the compte.
     */
    public function dossiers(): HasMany
    {
        return $this->hasMany(Dossier::class);
    }

    /**
     * Get the users associated with the compte.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
