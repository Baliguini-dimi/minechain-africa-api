<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ConfirmTwoFactorRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\VerifyTwoFactorRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->attemptLogin(
            $request->validated('email'),
            $request->validated('password')
        );

        if ($result['requires_2fa']) {
            return response()->json([
                'requires_2fa' => true,
                'user_id' => $result['user_id'],
            ]);
        }

        return response()->json([
            'requires_2fa' => false,
            'token' => $result['token'],
            'user' => $result['user'],
        ]);
    }

    public function verifyTwoFactor(VerifyTwoFactorRequest $request): JsonResponse
    {
        $user = User::findOrFail($request->validated('user_id'));

        $result = $this->authService->verifyTwoFactor(
            $user,
            $request->validated('code')
        );

        return response()->json([
            'token' => $result['token'],
            'user' => $result['user'],
        ]);
    }

    public function generateTwoFactorSecret(Request $request): JsonResponse
    {
        $result = $this->authService->generateTwoFactorSecret($request->user());

        return response()->json($result);
    }

    public function confirmTwoFactorEnrollment(ConfirmTwoFactorRequest $request): JsonResponse
    {
        $confirmed = $this->authService->confirmTwoFactorEnrollment(
            $request->user(),
            $request->validated('code')
        );

        return response()->json(['confirmed' => $confirmed]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie.']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user()->load('role'));
    }
}