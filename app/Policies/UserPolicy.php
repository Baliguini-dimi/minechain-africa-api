<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin_technique', 'admin_organisation']);
    }

    public function view(User $user, User $target): bool
    {
        if ($user->hasRole('super_admin_technique')) {
            return true;
        }

        if ($user->hasRole('admin_organisation')) {
            return $user->organization_id === $target->organization_id;
        }

        return $user->id === $target->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super_admin_technique', 'admin_organisation']);
    }

    public function update(User $user, User $target): bool
    {
        if ($user->hasRole('super_admin_technique')) {
            return true;
        }

        if ($user->hasRole('admin_organisation')) {
            return $user->organization_id === $target->organization_id;
        }

        return $user->id === $target->id;
    }

    /**
     * Pas de suppression physique — un Admin Organisation "suspend" un utilisateur
     * (changement de statut), il ne le supprime jamais réellement.
     */
    public function delete(User $user, User $target): bool
    {
        return false;
    }
}