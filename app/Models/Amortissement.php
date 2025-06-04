<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amortissement extends Model
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = "plans_amortissement";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'immobilisation_id',
        'date_amortissement',
        'montant',
        'type_amortissement',
        'commentaire',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_amortissement' => 'date',
        'montant' => 'decimal:2',
    ];

    /**
     * Get the immobilisation that owns the amortissement.
     */
    public function immobilisation()
    {
        return $this->belongsTo(Immobilisation::class);
    }

    /**
     * Get the user that created the amortissement.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include amortissements for a specific dossier.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $dossierId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDossier($query, $dossierId)
    {
        return $query->whereHas('immobilisation', function ($query) use ($dossierId) {
            $query->where('dossier_id', $dossierId);
        });
    }
}
