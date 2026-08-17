<?php

namespace App\Policies;

use App\Models\User;

class AuditLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin_technique', 'admin_organisation', 'superviseur']);
    }
}