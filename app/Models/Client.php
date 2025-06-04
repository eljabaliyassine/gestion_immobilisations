<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'societe_id',
        'code',
        'nom',
        'identifiant_unique',
        'adresse',
        'code_postal',
        'ville',
        'pays',
        'telephone',
        'email',
        'site_web',
        'siret',
        'actif',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array
     */
    protected $casts = [
        'actif' => 'boolean',
    ];

    /**
     * Obtenir la société associée à ce client.
     */
    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }

    /**
     * Obtenir les dossiers associés à ce client.
     */
    public function dossiers()
    {
        return $this->hasMany(Dossier::class);
    }

    /**
     * Obtenir les utilisateurs associés à ce client.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Scope pour filtrer les clients actifs.
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope pour filtrer les clients par société.
     */
    public function scopeSociete($query, $societeId)
    {
        return $query->where('societe_id', $societeId);
    }
}
