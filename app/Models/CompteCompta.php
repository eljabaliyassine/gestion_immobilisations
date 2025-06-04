<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompteCompta extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comptescompta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dossier_id',
        'numero',
        'libelle',
        'type',
        'est_actif',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'est_actif' => 'boolean',
    ];

    /**
     * Get the dossier that owns the compte comptable.
     */
    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class);
    }

    /**
     * Get the immobilisations that use this compte comptable.
     */
    public function immobilisations(): HasMany
    {
        return $this->hasMany(Immobilisation::class, 'comptecompta_id');
    }

    /**
     * Get the familles that use this compte as immobilisation compte.
     */
    public function famillesImmobilisation(): HasMany
    {
        return $this->hasMany(Famille::class, 'comptecompta_immobilisation_id');
    }

    /**
     * Get the familles that use this compte as amortissement compte.
     */
    public function famillesAmortissement(): HasMany
    {
        return $this->hasMany(Famille::class, 'comptecompta_amortissement_id');
    }

    /**
     * Get the familles that use this compte as dotation compte.
     */
    public function famillesDotation(): HasMany
    {
        return $this->hasMany(Famille::class, 'comptecompta_dotation_id');
    }
}
