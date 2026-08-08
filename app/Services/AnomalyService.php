<?php

namespace App\Services;

use App\Models\Anomaly;
use App\Models\Lot;
use App\Models\User;
use App\Repositories\Contracts\AnomalyRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AnomalyService
{
    public function __construct(
        protected AnomalyRepositoryInterface $anomalyRepository,
        protected PassportChainService $passportChainService
    ) {
    }

    /**
     * Un agent (ou le système IA plus tard) déclare une anomalie sur un lot :
     * le lot passe en statut "anomaly", un événement est ajouté à la chaîne.
     */
    public function report(Lot $lot, array $data, User $actor): Anomaly
    {
        return DB::transaction(function () use ($lot, $data, $actor) {
            $anomaly = $this->anomalyRepository->create([
                ...$data,
                'lot_id' => $lot->id,
                'detected_by' => 'agent',
                'status' => 'open',
                'reported_by_user_id' => $actor->id,
            ]);

            $lot->update(['status' => 'anomaly']);

            $this->passportChainService->appendEvent(
                $lot->passport,
                'anomaly',
                $actor,
                payload: [
                    'anomaly_id' => $anomaly->id,
                    'type' => $anomaly->type,
                    'severity' => $anomaly->severity,
                ]
            );

            return $anomaly;
        });
    }

    /**
     * Résolution ou classement sans suite d'une anomalie par un Superviseur.
     * Ne remet PAS automatiquement le lot en "in_transit" — c'est une décision
     * humaine volontaire, faite via un endpoint de reprise séparé si besoin.
     */
    public function resolve(Anomaly $anomaly, string $resolution, User $actor): Anomaly
    {
        return $this->anomalyRepository->update($anomaly, [
            'status' => $resolution,
            'resolved_by_user_id' => $actor->id,
            'resolved_at' => now(),
        ]);
    }
}