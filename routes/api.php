<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AnomalyController;
use App\Http\Controllers\Api\V1\CheckpointControlController;
use App\Http\Controllers\Api\V1\CheckpointController;
use App\Http\Controllers\Api\V1\LotController;
use App\Http\Controllers\Api\V1\OrganizationController;
use App\Http\Controllers\Api\V1\ResourceTypeController;
use App\Http\Controllers\Api\V1\SourceController;
use App\Http\Controllers\Api\V1\UserController;
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
        Route::apiResource('users', UserController::class)->except(['destroy']);
        Route::post('/users/{user}/suspend', [UserController::class, 'suspend']);
        Route::post('/users/{user}/reactivate', [UserController::class, 'reactivate']);
        Route::apiResource('sources', SourceController::class)->except(['destroy']);
        Route::apiResource('resource-types', ResourceTypeController::class)->except(['destroy']);
        Route::apiResource('lots', LotController::class)->except(['destroy']);
        Route::post('/lots/{lot}/depart', [LotController::class, 'markAsDeparted']);
        Route::get('/checkpoints', [CheckpointController::class, 'index']);
        Route::post('/checkpoint-controls', [CheckpointControlController::class, 'store']);
        Route::post('/lots/{lot}/anomalies', [AnomalyController::class, 'store']);
        Route::post('/anomalies/{anomaly}/resolve', [AnomalyController::class, 'resolve']);
    });
});