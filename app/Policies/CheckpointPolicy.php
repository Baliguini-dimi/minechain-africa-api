<?php

namespace App\Policies;

use App\Models\Checkpoint;
use App\Models\User;

class CheckpointPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Checkpoint $checkpoint): bool
    {
        return true;
    }

    /**
     * Seul un Agent Checkpoint peut enregistrer un contrôle
     * (04-roles-et-permissions.md §5).
     */
    public function registerControl(User $user): bool
    {
        return $user->hasRole('agent_checkpoint');
    }
}