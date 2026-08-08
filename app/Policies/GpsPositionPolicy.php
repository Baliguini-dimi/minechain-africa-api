<?php

namespace App\Policies;

use App\Models\Lot;
use App\Models\User;

class GpsPositionPolicy
{
    /**
     * Enregistrement d'une position : réservé au flux automatisé de la balise
     * (pas d'utilisateur humain normalement), mais on autorise Admin Organisation
     * et Superviseur pour les tests/injections manuelles en attendant l'intégration IoT réelle.
     */
    public function record(User $user, Lot $lot): bool
    {
        if ($user->organization_id !== $lot->organization_id) {
            return false;
        }

        return $user->hasAnyRole(['admin_organisation', 'superviseur']);
    }

    public function viewHistory(User $user, Lot $lot): bool
    {
        if ($user->hasRole('super_admin_technique')) {
            return true;
        }

        return $user->organization_id === $lot->organization_id;
    }
}