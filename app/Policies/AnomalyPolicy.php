<?php

namespace App\Policies;

use App\Models\Anomaly;
use App\Models\User;

class AnomalyPolicy
{
    /**
     * Agent Checkpoint et Superviseur peuvent signaler une anomalie
     * (04-roles-et-permissions.md §4 et §5).
     */
    public function report(User $user): bool
    {
        return $user->hasAnyRole(['agent_checkpoint', 'superviseur']);
    }

    /**
     * Seul un Superviseur peut résoudre/classer une anomalie.
     */
    public function resolve(User $user, Anomaly $anomaly): bool
    {
        return $user->hasRole('superviseur');
    }
}