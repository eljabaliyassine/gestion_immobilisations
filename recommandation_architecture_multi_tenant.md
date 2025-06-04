# Recommandation d'architecture multi-tenant pour l'application de gestion des immobilisations

## Introduction

Suite à l'analyse de votre application et des problèmes rencontrés, voici une recommandation détaillée pour optimiser l'architecture multi-tenant de votre système. Cette architecture vise à répondre au besoin exprimé où un superadmin crée une société, puis un client associé à cette société, et un adminclient associé à client_id et societe_id qui crée les utilisateurs.

## Architecture proposée

### 1. Structure des entités

#### Hiérarchie des entités
```
Societe
  └── Client
       └── Dossier
            └── Immobilisations, Contrats, etc.
```

#### Hiérarchie des utilisateurs
```
SuperAdmin (niveau global)
  └── AdminClient (niveau client)
       └── User (niveau dossier)
```

### 2. Modèles et relations

#### Modèle Societe
```php
class Societe extends Model
{
    protected $fillable = [
        'nom',
        'adresse',
        'telephone',
        'email',
        'siret',
        // autres attributs pertinents
    ];

    // Relations
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
```

#### Modèle Client
```php
class Client extends Model
{
    protected $fillable = [
        'nom',
        'adresse',
        'telephone',
        'email',
        'societe_id',
        // autres attributs pertinents
    ];

    // Relations
    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }

    public function dossiers()
    {
        return $this->hasMany(Dossier::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
```

#### Modèle Dossier (existant, à adapter)
```php
class Dossier extends Model
{
    protected $fillable = [
        'nom',
        'code',
        'libelle',
        'client_id',
        'societe_id', // Redondance intentionnelle pour optimisation des requêtes
        'exercice_debut',
        'exercice_fin',
        'est_cloture',
        // autres attributs pertinents
    ];

    // Relations
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'dossier_user');
    }

    public function immobilisations()
    {
        return $this->hasMany(Immobilisation::class);
    }

    // Autres relations avec les entités métier
}
```

#### Modèle User (adapté)
```php
class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'societe_id', // Peut être null pour SuperAdmin
        'client_id',  // Peut être null pour SuperAdmin
        'role_id',
        'current_dossier_id',
    ];

    // Relations
    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function dossiers()
    {
        return $this->belongsToMany(Dossier::class, 'dossier_user');
    }

    public function currentDossier()
    {
        return $this->belongsTo(Dossier::class, 'current_dossier_id');
    }

    // Méthodes de vérification des rôles et permissions
    public function hasRole(string $roleName): bool
    {
        if (!$this->role) {
            return false;
        }
        
        return $this->role->name === $roleName;
    }

    public function hasPermission(string $permissionName): bool
    {
        if (!$this->role) {
            return false;
        }
        
        return $this->role->permissions()->where('name', $permissionName)->exists();
    }

    // Méthodes d'accès contextuelles
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isAdminClient(): bool
    {
        return $this->hasRole('admin_client');
    }

    // Scope pour filtrer les utilisateurs par société
    public function scopeSociete($query, $societeId)
    {
        return $query->where('societe_id', $societeId);
    }

    // Scope pour filtrer les utilisateurs par client
    public function scopeClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }
}
```

### 3. Gestion des rôles et permissions

#### Modèle Role
```php
class Role extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    // Relations
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
```

#### Modèle Permission
```php
class Permission extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    // Relations
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}
```

### 4. Middlewares pour la sécurité multi-tenant

#### Middleware EnsureDossierIsSelected (existant)
Ce middleware vérifie qu'un dossier est sélectionné avant d'accéder aux routes protégées.

#### Nouveau middleware EnsureUserHasAccess
```php
class EnsureUserHasAccess
{
    public function handle($request, Closure $next, $type)
    {
        $user = Auth::user();
        
        switch ($type) {
            case 'superadmin':
                if (!$user->isSuperAdmin()) {
                    abort(403, 'Accès non autorisé.');
                }
                break;
                
            case 'adminclient':
                if (!$user->isAdminClient() && !$user->isSuperAdmin()) {
                    abort(403, 'Accès non autorisé.');
                }
                break;
                
            case 'client':
                // Vérifier que l'utilisateur a accès au client spécifié
                $clientId = $request->route('client');
                if ($clientId && $user->client_id != $clientId && !$user->isSuperAdmin()) {
                    abort(403, 'Accès non autorisé à ce client.');
                }
                break;
                
            case 'societe':
                // Vérifier que l'utilisateur a accès à la société spécifiée
                $societeId = $request->route('societe');
                if ($societeId && $user->societe_id != $societeId && !$user->isSuperAdmin()) {
                    abort(403, 'Accès non autorisé à cette société.');
                }
                break;
        }
        
        return $next($request);
    }
}
```

### 5. Contrôleurs adaptés

#### SocieteController (pour SuperAdmin)
```php
class SocieteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ensure.access:superadmin');
    }
    
    // Méthodes CRUD pour gérer les sociétés
}
```

#### ClientController (pour SuperAdmin et AdminClient)
```php
class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ensure.access:adminclient');
    }
    
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            // SuperAdmin voit tous les clients
            $clients = Client::all();
        } else {
            // AdminClient ne voit que les clients de sa société
            $clients = Client::where('societe_id', $user->societe_id)->get();
        }
        
        return view('clients.index', compact('clients'));
    }
    
    // Autres méthodes CRUD avec vérifications d'accès similaires
}
```

#### UserController (pour SuperAdmin et AdminClient)
```php
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ensure.access:adminclient');
    }
    
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            // SuperAdmin peut voir tous les utilisateurs
            $users = User::all();
        } elseif ($user->isAdminClient()) {
            // AdminClient ne voit que les utilisateurs de son client
            $users = User::where('client_id', $user->client_id)->get();
        }
        
        return view('users.index', compact('users'));
    }
    
    // Autres méthodes CRUD avec vérifications d'accès similaires
}
```

## Flux de création des entités

### 1. Création d'une société (par SuperAdmin)
- Le SuperAdmin crée une société via le SocieteController
- La société est enregistrée dans la base de données

### 2. Création d'un client (par SuperAdmin)
- Le SuperAdmin crée un client associé à une société via le ClientController
- Le client est enregistré avec une référence à la société (societe_id)

### 3. Création d'un AdminClient (par SuperAdmin)
- Le SuperAdmin crée un utilisateur avec le rôle 'admin_client'
- L'utilisateur est associé à la fois à une société (societe_id) et à un client (client_id)

### 4. Création d'utilisateurs standards (par AdminClient)
- L'AdminClient crée des utilisateurs standards (rôle 'user')
- Ces utilisateurs sont automatiquement associés au même client et à la même société que l'AdminClient

### 5. Création de dossiers (par AdminClient)
- L'AdminClient crée des dossiers pour son client
- Les dossiers sont associés au client (client_id) et à la société (societe_id)
- Les utilisateurs peuvent ensuite être associés à ces dossiers via la table pivot dossier_user

## Avantages de cette architecture

1. **Séparation claire des niveaux d'accès** : SuperAdmin > AdminClient > User
2. **Isolation des données** : Chaque client ne voit que ses propres données
3. **Flexibilité** : Une société peut avoir plusieurs clients
4. **Performance** : Utilisation de clés étrangères pour optimiser les requêtes
5. **Sécurité renforcée** : Middlewares dédiés pour vérifier les accès à chaque niveau
6. **Évolutivité** : Architecture adaptée à l'ajout de nouveaux niveaux ou entités

## Migrations nécessaires

Pour implémenter cette architecture, vous devrez créer ou modifier les tables suivantes :

1. Table `societes` (nouvelle)
2. Table `clients` (nouvelle)
3. Table `dossiers` (modification pour ajouter client_id)
4. Table `users` (modification pour ajouter client_id et societe_id)
5. Table `roles` et `permissions` (vérifier qu'elles existent)
6. Table pivot `role_permission` (vérifier qu'elle existe)
7. Table pivot `dossier_user` (vérifier qu'elle existe)

## Conclusion

Cette architecture multi-tenant hiérarchique offre une solution robuste et évolutive pour votre application de gestion des immobilisations. Elle permet une séparation claire des responsabilités et des accès, tout en maintenant des performances optimales grâce à une structure de base de données bien conçue.

La mise en œuvre de cette architecture nécessitera des modifications dans votre code existant, mais les bénéfices en termes de sécurité, de maintenabilité et d'évolutivité justifient largement cet investissement.
