<?php

namespace App\Policies;

use App\Models\Lot;
use App\Models\User;

class LotPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin_technique', 'admin_organisation', 'superviseur']);
    }

    public function view(User $user, Lot $lot): bool
    {
        if ($user->hasRole('super_admin_technique')) {
            return true;
        }

        return $user->organization_id === $lot->organization_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin_organisation');
    }

    public function update(User $user, Lot $lot): bool
    {
        if ($user->organization_id !== $lot->organization_id) {
            return false;
        }

        return $user->hasAnyRole(['admin_organisation', 'superviseur']);
    }

    /**
     * Expédition d'un lot : nécessite validation Superviseur
     * (04-roles-et-permissions.md §6 — opération critique).
     */
    public function markAsDeparted(User $user, Lot $lot): bool
    {
        if ($user->organization_id !== $lot->organization_id) {
            return false;
        }

        return $user->hasRole('superviseur');
    }

    public function delete(User $user, Lot $lot): bool
    {
        return false;
    }
}