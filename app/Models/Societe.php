<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Societe extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'nom',
        'adresse',
        'code_postal',
        'ville',
        'pays',
        'telephone',
        'email',
        'siret',
        'est_actif',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array
     */
    protected $casts = [
        'est_actif' => 'boolean',
    ];

    /**
     * Obtenir les clients associés à cette société.
     */
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    /**
     * Obtenir les dossiers associés à cette société.
     */
    public function dossiers()
    {
        return $this->hasMany(Dossier::class);
    }

    /**
     * Obtenir les utilisateurs associés à cette société via les clients.
     */
    public function users()
    {
        return $this->hasManyThrough(User::class, Client::class);
    }

    /**
     * Scope pour filtrer les sociétés actives.
     */
    public function scopeActif($query)
    {
        return $query->where('est_actif', true);
    }
}
