<?php

namespace App\Services;

use App\Models\CheckpointControl;
use App\Models\User;
use App\Repositories\Contracts\CheckpointControlRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckpointControlService
{
    public function __construct(
        protected CheckpointControlRepositoryInterface $checkpointControlRepository,
        protected QrCodeService $qrCodeService,
        protected PassportChainService $passportChainService,
        protected AiAnomalyDetectionService $aiAnomalyDetectionService
    ) {
    }

    /**
     * Flux complet du scan checkpoint (01-cahier-des-charges.md §5) :
     * 1. Vérifie le QR scanné et identifie le lot
     * 2. Enregistre le contrôle
     * 3. Ajoute un événement "checkpoint_control" à la chaîne du passeport
     */
    public function registerControl(
        string $qrToken,
        int $checkpointId,
        User $agent,
        string $status,
        ?string $observations = null,
        ?float $measuredWeight = null
    ): CheckpointControl {
        $lot = $this->qrCodeService->verifyToken($qrToken);

        if (! $lot) {
            throw ValidationException::withMessages([
                'qr_token' => ["Ce QR code n'est associé à aucun lot valide."],
            ]);
        }

        return DB::transaction(function () use ($lot, $checkpointId, $agent, $status, $observations, $measuredWeight) {
            $control = $this->checkpointControlRepository->create([
                'checkpoint_id' => $checkpointId,
                'lot_id' => $lot->id,
                'agent_user_id' => $agent->id,
                'control_datetime' => now(),
                'status' => $status,
                'measured_weight' => $measuredWeight,
                'observations' => $observations,
            ]);

            $this->passportChainService->appendEvent(
                $lot->passport,
                'checkpoint_control',
                $agent,
                payload: [
                    'checkpoint_id' => $checkpointId,
                    'status' => $status,
                    'observations' => $observations,
                    'measured_weight' => $measuredWeight,
                ]
            );

            if ($measuredWeight !== null) {
                $this->aiAnomalyDetectionService->analyzeWeight($control, (float) $lot->weight_volume);
            }

            return $control;
        });
    }
}