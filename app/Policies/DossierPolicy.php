<?php

namespace App\Policies;

use App\Models\Dossier;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DossierPolicy
{
    /**
     * Determine whether the user can view any models.
     * Users can generally view dossiers within their own compte.
     */
    public function viewAny(User $user): bool
    {
        // Check if the user belongs to a compte
        return !is_null($user->compte_id);
    }

    /**
     * Determine whether the user can view the model.
     * Allows viewing if the user belongs to the same compte as the dossier.
     */
    public function view(User $user, Dossier $dossier): bool
    {
        return $user->compte_id === $dossier->compte_id;
    }

    /**
     * Determine whether the user can create models.
     * Allows creation if the user belongs to a compte and has the necessary role (e.g., compte-admin).
     */
    public function create(User $user): bool
    {
        // Example: Check if user belongs to a compte and has admin role for that compte
        return !is_null($user->compte_id) && $user->hasRole("compte-admin"); // Assuming compte-admin role
    }

    /**
     * Determine whether the user can update the model.
     * Allows updating if the user belongs to the same compte and has the necessary role.
     */
    public function update(User $user, Dossier $dossier): bool
    {
        return $user->compte_id === $dossier->compte_id && $user->hasRole("compte-admin");
    }

    /**
     * Determine whether the user can delete the model.
     * Allows deletion if the user belongs to the same compte and has the necessary role.
     */
    public function delete(User $user, Dossier $dossier): bool
    {
        // Add additional checks? e.g., cannot delete if it contains data?
        return $user->compte_id === $dossier->compte_id && $user->hasRole("compte-admin");
    }

    /**
     * Determine whether the user can restore the model.
     * (Optional - if using soft deletes)
     */
    // public function restore(User $user, Dossier $dossier): bool
    // {
    //     return $user->compte_id === $dossier->compte_id && $user->hasRole("compte-admin");
    // }

    /**
     * Determine whether the user can permanently delete the model.
     * (Optional - if using soft deletes)
     */
    // public function forceDelete(User $user, Dossier $dossier): bool
    // {
    //     return $user->compte_id === $dossier->compte_id && $user->hasRole("compte-admin");
    // }
}

