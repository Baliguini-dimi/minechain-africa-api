<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;

class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected Google2FA $google2fa
    ) {
    }

    /**
     * Étape 1 de connexion : vérifie email + mot de passe.
     * Retourne l'utilisateur si le 2FA n'est pas activé (connexion directe),
     * ou signale qu'un code 2FA est requis.
     */
    public function attemptLogin(string $email, string $password): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ["Les identifiants fournis ne correspondent à aucun compte."],
            ]);
        }

        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ["Ce compte n'est pas actif."],
            ]);
        }

        if ($user->two_factor_enabled) {
            return [
                'requires_2fa' => true,
                'user_id' => $user->id,
            ];
        }

        return [
            'requires_2fa' => false,
            'user' => $user->load('role'),
            'token' => $this->issueToken($user),
        ];
    }

    /**
     * Étape 2 de connexion : vérifie le code TOTP et émet le token final.
     */
    public function verifyTwoFactor(User $user, string $code): array
    {
        $valid = $this->google2fa->verifyKey($user->two_factor_secret, $code);

        if (! $valid) {
            throw ValidationException::withMessages([
                'code' => ["Le code de vérification est invalide."],
            ]);
        }

        return [
            'user' => $user->load('role'),
            'token' => $this->issueToken($user),
        ];
    }

    /**
     * Génère le secret 2FA et l'URL du QR code pour l'enrôlement.
     */
    public function generateTwoFactorSecret(User $user): array
    {
        $secret = $this->google2fa->generateSecretKey();

        $this->userRepository->update($user, [
            'two_factor_secret' => $secret,
        ]);

        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return [
            'secret' => $secret,
            'qr_code_url' => $qrCodeUrl,
        ];
    }

    /**
     * Confirme l'enrôlement 2FA après vérification du premier code.
     */
    public function confirmTwoFactorEnrollment(User $user, string $code): bool
    {
        $valid = $this->google2fa->verifyKey($user->two_factor_secret, $code);

        if ($valid) {
            $this->userRepository->update($user, [
                'two_factor_enabled' => true,
            ]);
        }

        return $valid;
    }

    protected function issueToken(User $user): string
    {
        return $user->createToken('api-token')->plainTextToken;
    }
}