<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EcheanceCreditBail extends Model
{
    use HasFactory;

    protected $table = "echeances_credit_bail";

    protected $fillable = [
        "detail_credit_bail_contrat_id",
        "numero_echeance",
        "date_echeance",
        "montant_redevance",
        "part_interet",
        "part_capital",
        "capital_restant_du",
        "statut",
        "date_paiement",
    ];

    protected $casts = [
        "date_echeance" => "date",
        "date_paiement" => "date",
        "montant_redevance" => "decimal:2",
        "part_interet" => "decimal:2",
        "part_capital" => "decimal:2",
        "capital_restant_du" => "decimal:2",
    ];

    /**
     * Get the credit-bail details associated with this echeance.
     */
    public function detailCreditBail(): BelongsTo
    {
        return $this->belongsTo(DetailCreditBail::class, "detail_credit_bail_contrat_id", "contrat_id");
    }

    // Accessor to get the contrat indirectly
    public function getContratAttribute()
    {
        return $this->detailCreditBail->contrat;
    }

    // Accessor to get the dossier indirectly
    public function getDossierAttribute()
    {
        return $this->contrat->dossier;
    }
}
