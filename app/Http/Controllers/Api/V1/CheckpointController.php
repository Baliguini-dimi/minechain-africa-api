<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CheckpointResource;
use App\Models\Checkpoint;
use App\Repositories\Contracts\CheckpointRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CheckpointController extends Controller
{
    public function __construct(protected CheckpointRepositoryInterface $checkpointRepository)
    {
    }

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Checkpoint::class);

        $checkpoints = $this->checkpointRepository->listAll();

        return response()->json(['data' => CheckpointResource::collection($checkpoints)]);
    }
}