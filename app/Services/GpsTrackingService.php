<?php

namespace App\Services;

use App\Models\GpsPosition;
use App\Models\Lot;
use App\Repositories\Contracts\GpsDeviceRepositoryInterface;
use App\Repositories\Contracts\GpsPositionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GpsTrackingService
{
    public function __construct(
        protected GpsDeviceRepositoryInterface $gpsDeviceRepository,
        protected GpsPositionRepositoryInterface $gpsPositionRepository
    ) {
    }

    /**
     * Associe une balise GPS existante à un lot expédié
     * (01-cahier-des-charges.md §4 — dispositifs de suivi).
     */
    public function assignDeviceToLot(string $deviceIdentifier, Lot $lot): void
    {
        $device = $this->gpsDeviceRepository->findByIdentifier($deviceIdentifier);

        if (! $device) {
            throw ValidationException::withMessages([
                'device_identifier' => ["Aucune balise GPS ne correspond à cet identifiant."],
            ]);
        }

        if ($device->lot_id !== null) {
            throw ValidationException::withMessages([
                'device_identifier' => ["Cette balise est déjà affectée à un autre lot."],
            ]);
        }

        $this->gpsDeviceRepository->update($device, [
            'lot_id' => $lot->id,
            'status' => 'active',
        ]);
    }

    /**
     * Enregistre une position GPS reçue de la balise.
     * La détection d'anomalie de déviation/arrêt est déléguée au futur
     * micro-service Python IA (02-stack-technique.md §5) — ici on stocke
     * juste la position brute avec le flag par défaut à false.
     */
    public function recordPosition(Lot $lot, array $data): GpsPosition
    {
        return DB::transaction(function () use ($lot, $data) {
            $device = $lot->gpsPositions()->exists()
                ? $lot->gpsPositions()->latest('recorded_at')->first()->gpsDevice
                : $this->gpsDeviceRepository->findByIdentifier($data['device_identifier'] ?? '');

            if (! $device) {
                throw ValidationException::withMessages([
                    'device_identifier' => ["Aucune balise GPS active n'est associée à ce lot."],
                ]);
            }

            return $this->gpsPositionRepository->create([
                'gps_device_id' => $device->id,
                'lot_id' => $lot->id,
                'lat' => $data['lat'],
                'lng' => $data['lng'],
                'speed' => $data['speed'] ?? null,
                'recorded_at' => now(),
                'is_anomaly_flagged' => false,
            ]);
        });
    }

    public function history(Lot $lot): Collection
    {
        return $this->gpsPositionRepository->listForLot($lot);
    }
}