<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignGpsDeviceRequest;
use App\Http\Requests\RecordGpsPositionRequest;
use App\Http\Resources\GpsPositionResource;
use App\Models\GpsPosition;
use App\Models\Lot;
use App\Services\GpsTrackingService;
use Illuminate\Http\JsonResponse;

class GpsTrackingController extends Controller
{
    public function __construct(protected GpsTrackingService $gpsTrackingService)
    {
    }

    public function assignDevice(AssignGpsDeviceRequest $request, Lot $lot): JsonResponse
    {
        $this->gpsTrackingService->assignDeviceToLot(
            $request->validated('device_identifier'),
            $lot
        );

        return response()->json(['message' => 'Balise GPS associée avec succès.']);
    }

    public function recordPosition(RecordGpsPositionRequest $request, Lot $lot): JsonResponse
    {
        $position = $this->gpsTrackingService->recordPosition($lot, $request->validated());

        return response()->json(new GpsPositionResource($position), 201);
    }

    public function history(Lot $lot): JsonResponse
    {
        $this->authorize('viewHistory', [GpsPosition::class, $lot]);

        $positions = $this->gpsTrackingService->history($lot);

        return response()->json(['data' => GpsPositionResource::collection($positions)]);
    }
}