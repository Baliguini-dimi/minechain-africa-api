<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLotRequest;
use App\Http\Requests\UpdateLotRequest;
use App\Http\Resources\LotResource;
use App\Models\Lot;
use App\Services\LotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LotController extends Controller
{
    public function __construct(protected LotService $lotService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Lot::class);

        $lots = $this->lotService->listByOrganization($request->user()->organization_id);

        return response()->json(LotResource::collection($lots));
    }

    public function show(Lot $lot): JsonResponse
    {
        $this->authorize('view', $lot);

        return response()->json(new LotResource($this->lotService->find($lot->id)));
    }

    public function store(StoreLotRequest $request): JsonResponse
    {
        $lot = $this->lotService->create($request->validated(), $request->user());

        return response()->json(new LotResource($lot), 201);
    }

    public function update(UpdateLotRequest $request, Lot $lot): JsonResponse
    {
        $updated = $this->lotService->find($lot->id);

        return response()->json(new LotResource($updated));
    }

    public function markAsDeparted(Request $request, Lot $lot): JsonResponse
    {
        $this->authorize('markAsDeparted', $lot);

        $updated = $this->lotService->markAsDeparted($lot, $request->user());

        return response()->json(new LotResource($updated));
    }

    public function markAsDelivered(Request $request, Lot $lot): JsonResponse
    {
        $this->authorize('markAsDelivered', $lot);

        $updated = $this->lotService->markAsDelivered($lot, $request->user());

        return response()->json(new LotResource($updated));
    }

    public function closePassport(Request $request, Lot $lot): JsonResponse
    {
        $this->authorize('closePassport', $lot);

        $updated = $this->lotService->closePassport($lot, $request->user());

        return response()->json(new LotResource($updated));
    }
}