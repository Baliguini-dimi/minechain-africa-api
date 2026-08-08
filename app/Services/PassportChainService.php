<?php

namespace App\Services;

use App\Models\Passport;
use App\Models\PassportEvent;
use App\Models\User;

class PassportChainService
{
    /**
     * Ajoute un nouveau bloc (événement) à la chaîne d'un passeport.
     * Calcule automatiquement prev_hash, hash et signature.
     */
    public function appendEvent(
        Passport $passport,
        string $eventType,
        User $actor,
        array $payload = [],
        ?array $location = null
    ): PassportEvent {
        $previousEvent = $passport->events()->latest('occurred_at')->first();
        $prevHash = $previousEvent?->hash;

        $occurredAt = now();

        $dataToHash = [
            'passport_id' => $passport->id,
            'event_type' => $eventType,
            'actor_user_id' => $actor->id,
            'location' => $location,
            'payload' => $payload,
            'prev_hash' => $prevHash,
            'occurred_at' => $occurredAt->toIso8601String(),
        ];

        $hash = $this->computeHash($dataToHash);
        $signature = $this->sign($hash);

        return $passport->events()->create([
            'event_type' => $eventType,
            'actor_user_id' => $actor->id,
            'location' => $location,
            'payload' => $payload,
            'prev_hash' => $prevHash,
            'hash' => $hash,
            'signature' => $signature,
            'occurred_at' => $occurredAt,
        ]);
    }

    /**
     * Vérifie l'intégrité complète de la chaîne d'un passeport :
     * chaque bloc doit correspondre au hash recalculé, et prev_hash doit
     * correspondre au hash du bloc précédent.
     */
    public function verifyChainIntegrity(Passport $passport): bool
    {
        $events = $passport->events()->orderBy('occurred_at')->get();

        $expectedPrevHash = null;

        foreach ($events as $event) {
            if ($event->prev_hash !== $expectedPrevHash) {
                return false;
            }

            $dataToHash = [
                'passport_id' => $passport->id,
                'event_type' => $event->event_type,
                'actor_user_id' => $event->actor_user_id,
                'location' => $event->location,
                'payload' => $event->payload,
                'prev_hash' => $event->prev_hash,
                'occurred_at' => $event->occurred_at->toIso8601String(),
            ];

            $recomputedHash = $this->computeHash($dataToHash);

            if ($recomputedHash !== $event->hash) {
                return false;
            }

            if (! $this->verifySignature($event->hash, $event->signature)) {
                return false;
            }

            $expectedPrevHash = $event->hash;
        }

        return true;
    }

    protected function computeHash(array $data): string
    {
        return hash('sha256', json_encode($data, JSON_UNESCAPED_SLASHES));
    }

    /**
     * Signature ECDSA du hash avec la clé privée de la plateforme.
     * Le couple de clés est généré une fois et stocké en configuration (.env).
     */
    protected function sign(string $hash): string
    {
        $privateKey = openssl_pkey_get_private($this->getPrivateKey());

        openssl_sign($hash, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        return base64_encode($signature);
    }

    protected function verifySignature(string $hash, string $signatureBase64): bool
    {
        $publicKey = openssl_pkey_get_public($this->getPublicKey());
        $signature = base64_decode($signatureBase64);

        return openssl_verify($hash, $signature, $publicKey, OPENSSL_ALGO_SHA256) === 1;
    }

    protected function getPrivateKey(): string
    {
        return str_replace('\n', "\n", config('services.passport_chain.private_key'));
    }

    protected function getPublicKey(): string
    {
        return str_replace('\n', "\n", config('services.passport_chain.public_key'));
    }
}