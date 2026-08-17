<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\Lot;
use App\Models\User;

class NotificationService
{
    /**
     * Notifie tous les utilisateurs actifs de l'organisation d'un lot,
     * a l'exception de l'auteur de l'action.
     */
    public function notifyOrganization(Lot $lot, string $type, array $payload, ?User $excludeActor = null): void
    {
        $recipients = User::where('organization_id', $lot->organization_id)
            ->where('status', 'active')
            ->when($excludeActor, fn ($q) => $q->where('id', '!=', $excludeActor->id))
            ->get();

        foreach ($recipients as $recipient) {
            AppNotification::create([
                'user_id' => $recipient->id,
                'type' => $type,
                'payload' => $payload,
            ]);
        }
    }

    public function markAsRead(AppNotification $notification): AppNotification
    {
        $notification->update(['read_at' => now()]);

        return $notification;
    }

    public function markAllAsRead(User $user): void
    {
        AppNotification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}