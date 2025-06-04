<?php

namespace App\Policies;

use App\Models\Compte;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ComptePolicy
{
    /**
     * Determine whether the user can view any models.
     * Assumes a super-admin role or similar for viewing all comptes.
     */
    public function viewAny(User $user): bool
    {
        // Example: Check if the user has a specific role (e.g., 'super-admin')
        return $user->hasRole("super-admin"); // You need to implement hasRole() method on User model
    }

    /**
     * Determine whether the user can view the model.
     * Allows viewing if the user is a super-admin or belongs to the compte.
     */
    public function view(User $user, Compte $compte): bool
    {
        return $user->hasRole("super-admin") || $user->compte_id === $compte->id;
    }

    /**
     * Determine whether the user can create models.
     * Assumes only super-admins can create new comptes.
     */
    public function create(User $user): bool
    {
        return $user->hasRole("super-admin");
    }

    /**
     * Determine whether the user can update the model.
     * Allows updating if the user is a super-admin or an admin of that specific compte.
     */
    public function update(User $user, Compte $compte): bool
    {
        // Example: Check for super-admin or compte-admin role
        return $user->hasRole("super-admin") || ($user->compte_id === $compte->id && $user->hasRole("compte-admin"));
    }

    /**
     * Determine whether the user can delete the model.
     * Assumes only super-admins can delete comptes.
     */
    public function delete(User $user, Compte $compte): bool
    {
        return $user->hasRole("super-admin");
    }

    /**
     * Determine whether the user can restore the model.
     * (Optional - if using soft deletes)
     */
    // public function restore(User $user, Compte $compte): bool
    // {
    //     return $user->hasRole("super-admin");
    // }

    /**
     * Determine whether the user can permanently delete the model.
     * (Optional - if using soft deletes)
     */
    // public function forceDelete(User $user, Compte $compte): bool
    // {
    //     return $user->hasRole("super-admin");
    // }
}

