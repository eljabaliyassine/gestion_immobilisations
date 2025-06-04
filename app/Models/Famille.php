<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Famille extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'familles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dossier_id',
        'code',
        'libelle',
        'comptecompta_immobilisation_id',
        'comptecompta_amortissement_id',
        'comptecompta_dotation_id',
        'duree_amortissement_par_defaut',
        'methode_amortissement_par_defaut',
    ];

    /**
     * Get the dossier that owns the famille.
     */
    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class);
    }

    /**
     * Get the compte comptable d'immobilisation associé à cette famille.
     */
    public function compteImmobilisation(): BelongsTo
    {
        return $this->belongsTo(CompteCompta::class, 'comptecompta_immobilisation_id');
    }

    /**
     * Get the compte comptable d'amortissement associé à cette famille.
     */
    public function compteAmortissement(): BelongsTo
    {
        return $this->belongsTo(CompteCompta::class, 'comptecompta_amortissement_id');
    }

    /**
     * Get the compte comptable de dotation associé à cette famille.
     */
    public function compteDotation(): BelongsTo
    {
        return $this->belongsTo(CompteCompta::class, 'comptecompta_dotation_id');
    }

    /**
     * Get the immobilisations for the famille.
     */
    public function immobilisations(): HasMany
    {
        return $this->hasMany(Immobilisation::class);
    }
}
