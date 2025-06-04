<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProvisionDepreciation extends Model
{
    use HasFactory;

    protected $table = "provisions_depreciations"; // Explicit table name

    protected $fillable = [
        "immobilisation_id",
        "annee_exercice",
        "type",
        "montant",
        "motif",
        "date_provision",
        "user_id",
    ];

    protected $casts = [
        "date_provision" => "date",
        "montant" => "decimal:2",
    ];

    /**
     * Get the immobilisation associated with this provision/depreciation.
     */
    public function immobilisation(): BelongsTo
    {
        return $this->belongsTo(Immobilisation::class);
    }

    /**
     * Get the user who recorded the provision/depreciation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessor to get the dossier indirectly
    public function getDossierAttribute()
    {
        return $this->immobilisation->dossier;
    }
}
