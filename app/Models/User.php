<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'client_id',
        'current_dossier_id',
    ];

    /**
     * Les attributs qui doivent être cachés pour la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Obtenir le rôle de l'utilisateur.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Obtenir le client associé à cet utilisateur.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Obtenir la société associée à cet utilisateur via le client.
     * Utilisation de hasOneThrough pour garantir la compatibilité avec l'eager loading.
     */
    public function societe()
    {
        return $this->hasOneThrough(
            Societe::class,
            Client::class,
            'id', // Clé étrangère sur la table clients
            'id', // Clé primaire sur la table societes
            'client_id', // Clé locale sur la table users
            'societe_id' // Clé locale sur la table clients
        );
    }

    /**
     * Obtenir le dossier actuellement sélectionné par l'utilisateur.
     */
    public function currentDossier()
    {
        return $this->belongsTo(Dossier::class, 'current_dossier_id');
    }

    /**
     * Obtenir les dossiers auxquels l'utilisateur a accès.
     */
    public function dossiers()
    {
        return $this->belongsToMany(Dossier::class);
    }

    /**
     * Vérifier si l'utilisateur a un rôle spécifique.
     *
     * @param string $roleName
     * @return bool
     */
    public function hasRole($roleName)
    {
        // Vérification robuste pour éviter les erreurs si le rôle est null
        if (!$this->role) {
            return false;
        }
        
        // Vérifier à la fois le champ 'name' et 'nom' pour la compatibilité
        if (isset($this->role->name)) {
            return $this->role->name === $roleName;
        } elseif (isset($this->role->nom)) {
            return $this->role->nom === $roleName;
        }
        
        return false;
    }

    /**
     * Vérifier si l'utilisateur a une permission spécifique.
     *
     * @param string $permissionName
     * @return bool
     */
    public function hasPermission($permissionName)
    {
        if (!$this->role) {
            return false;
        }
        
        return $this->role->permissions()->where('name', $permissionName)->exists();
    }

    /**
     * Vérifier si l'utilisateur est un super admin.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('super_admin');
    }

    /**
     * Vérifier si l'utilisateur est un admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Vérifier si l'utilisateur a accès à un dossier spécifique.
     *
     * @param Dossier $dossier
     * @return bool
     */
    public function canAccessDossier($dossier)
    {
        // Super admin peut accéder à tous les dossiers
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        // Admin peut accéder aux dossiers de son client
        if ($this->isAdmin() && $this->client_id === $dossier->client_id) {
            return true;
        }
        
        // Utilisateur standard peut accéder aux dossiers qui lui sont attribués
        return $this->dossiers()->where('dossier_id', $dossier->id)->exists();
    }
}
