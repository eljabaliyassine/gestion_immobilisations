<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'societe_id',
        'client_id',
        'code',
        'nom',
        'libelle',
        'exercice_debut',
        'exercice_fin',
        'est_cloture',
        'date_cloture',
        'description',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array
     */
    protected $casts = [
        'exercice_debut' => 'date',
        'exercice_fin' => 'date',
        'est_cloture' => 'boolean',
        'date_cloture' => 'date',
    ];

    /**
     * Obtenir la société associée à ce dossier.
     */
    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }

    /**
     * Obtenir le client associé à ce dossier.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Obtenir les utilisateurs qui ont accès à ce dossier.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Obtenir les immobilisations associées à ce dossier.
     */
    public function immobilisations()
    {
        return $this->hasMany(Immobilisation::class);
    }

    /**
     * Obtenir les contrats associés à ce dossier.
     */
    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }

    /**
     * Obtenir les comptes comptables associés à ce dossier.
     */
    public function comptesCompta()
    {
        return $this->hasMany(CompteCompta::class);
    }

    /**
     * Obtenir les familles d'immobilisations associées à ce dossier.
     */
    public function familles()
    {
        return $this->hasMany(Famille::class);
    }

    /**
     * Obtenir les sites associés à ce dossier.
     */
    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    /**
     * Obtenir les services associés à ce dossier.
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Scope pour filtrer les dossiers non clôturés.
     */
    public function scopeNonCloture($query)
    {
        return $query->where('est_cloture', false);
    }

    /**
     * Scope pour filtrer les dossiers par client.
     */
    public function scopeClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Scope pour filtrer les dossiers par société.
     */
    public function scopeSociete($query, $societeId)
    {
        return $query->where('societe_id', $societeId);
    }
}
