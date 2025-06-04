<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fournisseur extends Model
{
    use HasFactory;

    protected $fillable = [
    "dossier_id",
    "code",
    "nom",
    "adresse",
    "code_postal",
    "ville",
    "pays",
    "telephone",
    "email",
    "site_web",
    "siret",
    "contact_nom",
    "contact_telephone",
    "contact_email",
    "est_actif"
    ];

    /**
     * Get the dossier that owns the fournisseur.
     */
    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class);
    }

    /**
     * Get the immobilisations acquired from this fournisseur.
     */
    public function immobilisations(): HasMany
    {
        return $this->hasMany(Immobilisation::class);
    }

    /**
     * Get the contrats (credit-bail) associated with this fournisseur (financial institution).
     */
    public function contratsCreditBail(): HasMany
    {
        return $this->hasMany(Contrat::class, "fournisseur_id");
    }
}
