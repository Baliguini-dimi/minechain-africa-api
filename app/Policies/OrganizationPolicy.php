<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;

class OrganizationPolicy
{
    /**
     * Voir la liste des organisations (le filtrage par portée se fait dans le Service/Controller,
     * pas ici — cette méthode vérifie juste si l'utilisateur a le droit de lister quoi que ce soit).
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin_technique', 'super_admin_gouvernemental']);
    }

    public function view(User $user, Organization $organization): bool
    {
        if ($user->hasAnyRole(['super_admin_technique', 'super_admin_gouvernemental'])) {
            return true;
        }

        return $user->organization_id === $organization->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('super_admin_technique');
    }

    public function update(User $user, Organization $organization): bool
    {
        return $user->hasRole('super_admin_technique');
    }

    /**
     * Pas de suppression physique (voir 04-roles-et-permissions.md §6) —
     * cette méthode existe pour la complétude de la policy mais renvoie toujours false.
     * La "suppression" se fait via un changement de statut (suspended).
     */
    public function delete(User $user, Organization $organization): bool
    {
        return false;
    }
}