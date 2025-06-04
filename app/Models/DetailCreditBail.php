<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DetailCreditBail extends Model
{
    use HasFactory;

    protected $table = "details_credit_bail";
    protected $primaryKey = "contrat_id"; // Set primary key to contrat_id
    public $incrementing = false; // Primary key is not auto-incrementing

    protected $fillable = [
        "contrat_id",
        "duree_mois",
        "valeur_residuelle",
        "periodicite",
        "montant_redevance_periodique",
        "taux_interet_periodique",
        "montant_total_redevances",
    ];

    protected $casts = [
        "valeur_residuelle" => "decimal:2",
        "montant_redevance_periodique" => "decimal:2",
        "taux_interet_periodique" => "decimal:5",
        "montant_total_redevances" => "decimal:2",
    ];

    /**
     * Get the contrat associated with the credit-bail details.
     */
    public function contrat(): BelongsTo
    {
        return $this->belongsTo(Contrat::class, "contrat_id");
    }

    /**
     * Get the echeances for the credit-bail.
     */
    public function echeances(): HasMany
    {
        // Foreign key in echeances_credit_bail table is detail_credit_bail_contrat_id
        return $this->hasMany(EcheanceCreditBail::class, "detail_credit_bail_contrat_id");
    }

    // Accessor to get the dossier indirectly
    public function getDossierAttribute()
    {
        return $this->contrat->dossier;
    }
}
