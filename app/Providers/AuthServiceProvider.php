<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('access-admin', function (User $user) {
            // Vérifier si l'utilisateur a un rôle d'administrateur
            return $user->hasRole('admin') || $user->hasRole('super_admin');
        });
        
        Gate::define('viewAdminSection', function (User $user) {
            // Alias pour access-admin
            return $user->hasRole('admin') || $user->hasRole('super_admin');
        });
        
        Gate::define('manage-societes', function (User $user) {
            // Seul le super admin peut gérer les sociétés
            return $user->hasRole('super_admin');
        });
    }
}
