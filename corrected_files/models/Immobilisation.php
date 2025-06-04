<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Immobilisation extends Model
{
    use HasFactory;

    protected $fillable = [
        "dossier_id",
        "numero",
        "code",
        "libelle",
        "designation",
        "description",
        "famille_id",
        "site_id",
        "service_id",
        "date_acquisition",
        "date_mise_service",
        "valeur_acquisition",
        "tva_deductible",
        "comptecompta_immobilisation_id",
        "comptecompta_amortissement_id",
        "comptecompta_dotation_id",
        "comptecompta_tva_id",
        "duree_amortissement",
        "methode_amortissement",
        "coefficient_degressif",
        "base_amortissement",
        "valeur_residuelle",
        "amortissements_cumules",
        "statut",
        "date_sortie",
        "prix_vente",
        "fournisseur_id",
        "numero_facture",
        "numero_serie",
        "code_barre",
        "photo_path",
        "document_path",
    ];

    protected $casts = [
        "date_acquisition" => "date",
        "date_mise_service" => "date",
        "date_sortie" => "date",
        "valeur_acquisition" => "decimal:2",
        "tva_deductible" => "decimal:2",
        "base_amortissement" => "decimal:2",
        "valeur_residuelle" => "decimal:2",
        "amortissements_cumules" => "decimal:2",
        "prix_vente" => "decimal:2",
        "coefficient_degressif" => "decimal:2",
    ];

    // Relationships
    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class);
    }

    public function famille(): BelongsTo
    {
        return $this->belongsTo(Famille::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function compteImmobilisation(): BelongsTo
    {
        return $this->belongsTo(CompteCompta::class, 'comptecompta_immobilisation_id');
    }

    public function compteAmortissement(): BelongsTo
    {
        return $this->belongsTo(CompteCompta::class, 'comptecompta_amortissement_id');
    }

    public function compteDotation(): BelongsTo
    {
        return $this->belongsTo(CompteCompta::class, 'comptecompta_dotation_id');
    }

    public function compteTva(): BelongsTo
    {
        return $this->belongsTo(CompteCompta::class, 'comptecompta_tva_id');
    }

    public function planAmortissements(): HasMany
    {
        return $this->hasMany(PlanAmortissement::class);
    }

    public function mouvements(): HasMany
    {
        return $this->hasMany(MouvementImmobilisation::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    public function contrats(): BelongsToMany
    {
        return $this->belongsToMany(Contrat::class, "contrat_immobilisation")
                    ->withPivot('date_liaison', 'date_deliaison')
                    ->withTimestamps();
    }

    // --- Accessors & Mutators ---

    /**
     * Calculate the cumulative depreciation up to a given date.
     *
     * @param Carbon|null $date The date up to which to calculate depreciation (defaults to now).
     * @return float
     */
    public function getCumulAmortissementAttribute(?Carbon $date = null): float
    {
        $targetDate = $date ?? Carbon::now();

        // 1. Sum regular depreciation from PlanAmortissement
        $amortissementRegulier = $this->planAmortissements()
                                     ->where("annee_exercice", "<=", $targetDate->year)
                                     ->sum("dotation_annuelle");

        // 2. Sum exceptional depreciation from MouvementImmobilisation
        $amortissementExceptionnel = $this->mouvements()
                                        ->where("type_mouvement", "amortissement_exceptionnel")
                                        ->where("date_mouvement", "<=", $targetDate->toDateString())
                                        ->sum("valeur_mouvement");

        return $amortissementRegulier + $amortissementExceptionnel;
    }

    /**
     * Calculate the Net Book Value (VNC) at a given date.
     *
     * @param Carbon|null $date The date at which to calculate VNC (defaults to now).
     * @return float
     */
    public function getVncAttribute(?Carbon $date = null): float
    {
        $cumulAmortissement = $this->getCumulAmortissementAttribute($date);
        return max(0, $this->valeur_acquisition - $cumulAmortissement);
    }

    /**
     * Get the Net Book Value (VNC) specifically at the date of disposal.
     * Returns null if the asset hasn't been disposed of.
     *
     * @return float|null
     */
    public function getVncAtDisposalAttribute(): ?float
    {
        if ($this->statut !== "Cédé" && $this->statut !== "Rebut") {
            return null;
        }

        // Find the disposal movement to get the date
        $disposalMovement = $this->mouvements()
                                ->whereIn("type_mouvement", ["Cession", "Rebut"])
                                ->latest("date_mouvement") // Get the most recent disposal
                                ->first();

        if (!$disposalMovement || !$disposalMovement->date_mouvement) {
            // Use date_sortie if available
            if ($this->date_sortie) {
                return $this->getVncAttribute(Carbon::parse($this->date_sortie));
            }
            
            // Cannot calculate VNC without disposal date
            return null;
        }

        return $this->getVncAttribute(Carbon::parse($disposalMovement->date_mouvement));
    }

    /**
     * Scope a query to only include immobilisations for a specific dossier.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $dossierId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForDossier($query, $dossierId)
    {
        return $query->where('dossier_id', $dossierId);
    }

    /**
     * Scope a query to only include active immobilisations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereIn('statut', ['En service', 'En stock', 'En réparation']);
    }

    /**
     * Scope a query to only include disposed immobilisations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDisposed($query)
    {
        return $query->whereIn('statut', ['Cédé', 'Rebut']);
    }
}
