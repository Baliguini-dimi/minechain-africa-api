<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCheckpointControlRequest;
use App\Http\Resources\CheckpointControlResource;
use App\Services\CheckpointControlService;
use Illuminate\Http\JsonResponse;

class CheckpointControlController extends Controller
{
    public function __construct(protected CheckpointControlService $checkpointControlService)
    {
    }

    public function store(StoreCheckpointControlRequest $request): JsonResponse
    {
        $control = $this->checkpointControlService->registerControl(
            $request->validated('qr_token'),
            $request->validated('checkpoint_id'),
            $request->user(),
            $request->validated('status'),
            $request->validated('observations')
        );

        return response()->json(new CheckpointControlResource($control), 201);
    }
}