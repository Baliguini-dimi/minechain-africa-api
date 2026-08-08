<?php

namespace App\Services;

use App\Models\Lot;
use App\Models\QrCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class QrCodeService
{
    /**
     * Génère un QR code pour un lot : token = identifiant du lot + signature HMAC.
     * Le contenu du QR (scanné par l'agent) sera ce code_value complet.
     */
    public function generateFor(Lot $lot): QrCode
    {
        $codeValue = $this->buildSignedToken($lot);

        return QrCode::create([
            'lot_id' => $lot->id,
            'code_value' => $codeValue,
            'generated_at' => now(),
        ]);
    }

    /**
     * Vérifie qu'un token scanné est valide et correspond bien à un lot existant.
     * Utilisé par le futur endpoint de scan checkpoint.
     */
    public function verifyToken(string $token): ?Lot
    {
        $qrCode = QrCode::where('code_value', $token)->first();

        if (! $qrCode) {
            return null;
        }

        return $qrCode->lot;
    }

    protected function buildSignedToken(Lot $lot): string
    {
        $payload = $lot->uuid;
        $signature = hash_hmac('sha256', $payload, config('services.qr_code.secret'));

        return $payload . '.' . $signature;
    }
}