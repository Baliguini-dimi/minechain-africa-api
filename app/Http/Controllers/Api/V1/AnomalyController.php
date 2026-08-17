<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResolveAnomalyRequest;
use App\Http\Requests\StoreAnomalyRequest;
use App\Http\Resources\AnomalyResource;
use App\Models\Anomaly;
use App\Models\Lot;
use App\Services\AnomalyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnomalyController extends Controller
{
    public function __construct(protected AnomalyService $anomalyService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Anomaly::class);

        $anomalies = $this->anomalyService->listOpenForOrganization($request->user()->organization_id);

        return response()->json(['data' => AnomalyResource::collection($anomalies)]);
    }

    public function store(StoreAnomalyRequest $request, Lot $lot): JsonResponse
    {
        $anomaly = $this->anomalyService->report(
            $lot,
            $request->validated(),
            $request->user()
        );

        return response()->json(new AnomalyResource($anomaly), 201);
    }

    public function resolve(ResolveAnomalyRequest $request, Anomaly $anomaly): JsonResponse
    {
        $resolved = $this->anomalyService->resolve(
            $anomaly,
            $request->validated('resolution'),
            $request->user()
        );

        return response()->json(new AnomalyResource($resolved));
    }
}
