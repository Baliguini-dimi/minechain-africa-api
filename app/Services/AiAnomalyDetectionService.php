<?php

namespace App\Services;

use App\Models\Anomaly;
use App\Models\CheckpointControl;
use App\Models\GpsPosition;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAnomalyDetectionService
{
    /**
     * Envoie une position GPS au micro-service IA pour analyse.
     * Si une anomalie est detectee, cree automatiquement une entree
     * dans la table anomalies avec detected_by = 'system_ia' (03-base-de-donnees.md §6).
     */
    public function analyzeGpsPosition(GpsPosition $position): ?Anomaly
    {
        try {
            $response = Http::timeout(5)->post(
                config('services.ai_service.url') . '/api/v1/detect/gps',
                [
                    'lot_id' => $position->lot_id,
                    'lat' => (float) $position->lat,
                    'lng' => (float) $position->lng,
                    'speed' => $position->speed ? (float) $position->speed : null,
                    'recorded_at' => $position->recorded_at->toIso8601String(),
                ]
            );

            if (! $response->successful()) {
                Log::warning('Echec appel micro-service IA (GPS)', ['status' => $response->status()]);
                return null;
            }

            $result = $response->json();

            if (! $result['is_anomaly']) {
                return null;
            }

            $position->update(['is_anomaly_flagged' => true]);

            return Anomaly::create([
                'lot_id' => $position->lot_id,
                'type' => $result['anomaly_type'] ?? 'itineraire_inhabituel',
                'description' => $result['reason'] ?? 'Anomalie detectee par le systeme IA.',
                'severity' => $result['severity'] ?? 'faible',
                'detected_by' => 'system_ia',
                'status' => 'open',
            ]);
        } catch (\Exception $e) {
            // Le micro-service IA n'est pas critique au fonctionnement de l'API :
            // une panne de ce service ne doit jamais bloquer l'enregistrement d'une position GPS.
            Log::warning('Micro-service IA injoignable', ['message' => $e->getMessage()]);
            return null;
        }
    }

    public function analyzeWeight(CheckpointControl $control, float $expectedWeight): ?Anomaly
    {
        try {
            $response = Http::timeout(5)->post(
                config('services.ai_service.url') . '/api/v1/detect/weight',
                [
                    'lot_id' => $control->lot_id,
                    'checkpoint_control_id' => $control->id,
                    'measured_weight' => (float) $control->measured_weight,
                    'expected_weight' => $expectedWeight,
                    'recorded_at' => $control->control_datetime?->toIso8601String() ?? now()->toIso8601String(),
                ]
            );

            if (! $response->successful()) {
                Log::warning('Echec appel micro-service IA (poids)', ['status' => $response->status()]);
                return null;
            }

            $result = $response->json();

            if (! $result['is_anomaly']) {
                return null;
            }

            return Anomaly::create([
                'lot_id' => $control->lot_id,
                'type' => $result['anomaly_type'] ?? 'poids_inattendu',
                'description' => $result['reason'] ?? 'Anomalie de poids detectee par le systeme IA.',
                'severity' => $result['severity'] ?? 'moyenne',
                'detected_by' => 'system_ia',
                'status' => 'open',
            ]);
        } catch (\Exception $e) {
            Log::warning('Micro-service IA injoignable (poids)', ['message' => $e->getMessage()]);
            return null;
        }
    }
}