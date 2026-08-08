<?php

namespace App\Policies;

use App\Models\ResourceType;
use App\Models\User;

class ResourceTypePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->organization_id !== null || $user->hasRole('super_admin_technique');
    }

    public function view(User $user, ResourceType $resourceType): bool
    {
        if ($resourceType->organization_id === null) {
            return true;
        }

        return $user->organization_id === $resourceType->organization_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin_organisation');
    }

    public function update(User $user, ResourceType $resourceType): bool
    {
        if ($resourceType->organization_id === null) {
            return false;
        }

        return $user->hasRole('admin_organisation') && $user->organization_id === $resourceType->organization_id;
    }

    public function delete(User $user, ResourceType $resourceType): bool
    {
        return false;
    }
}