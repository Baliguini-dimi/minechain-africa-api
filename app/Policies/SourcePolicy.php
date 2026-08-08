<?php

namespace App\Policies;

use App\Models\Source;
use App\Models\User;

class SourcePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin_technique', 'admin_organisation', 'superviseur']);
    }

    public function view(User $user, Source $source): bool
    {
        if ($user->hasRole('super_admin_technique')) {
            return true;
        }

        return $user->organization_id === $source->organization_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin_organisation');
    }

    public function update(User $user, Source $source): bool
    {
        return $user->hasRole('admin_organisation') && $user->organization_id === $source->organization_id;
    }

    public function delete(User $user, Source $source): bool
    {
        return false;
    }
}