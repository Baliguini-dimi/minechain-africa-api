<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/login/verify-2fa', [AuthController::class, 'verifyTwoFactor']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/2fa/generate', [AuthController::class, 'generateTwoFactorSecret']);
        Route::post('/2fa/confirm', [AuthController::class, 'confirmTwoFactorEnrollment']);

        Route::apiResource('organizations', OrganizationController::class)->except(['destroy']);
    });
});