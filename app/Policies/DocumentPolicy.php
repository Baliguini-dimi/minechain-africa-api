<?php

namespace App\Policies;

use App\Models\Lot;
use App\Models\User;

class DocumentPolicy
{
    public function viewAny(User $user, Lot $lot): bool
    {
        if ($user->hasRole('super_admin_technique')) {
            return true;
        }

        return $user->organization_id === $lot->organization_id;
    }

    public function upload(User $user, Lot $lot): bool
    {
        if ($user->organization_id !== $lot->organization_id) {
            return false;
        }

        return $user->hasAnyRole(['admin_organisation', 'superviseur', 'agent_checkpoint']);
    }
}