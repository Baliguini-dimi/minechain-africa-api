<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PassportEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $organizationId = $request->user()->organization_id;

        $events = PassportEvent::whereHas('passport.lot', fn ($q) => $q->where('organization_id', $organizationId))
            ->with(['passport.lot', 'actor'])
            ->latest('occurred_at')
            ->limit(10)
            ->get()
            ->map(fn ($event) => [
                'id' => $event->id,
                'event_type' => $event->event_type,
                'lot_uuid' => $event->passport->lot->uuid,
                'lot_id' => $event->passport->lot->id,
                'actor_name' => $event->actor->name,
                'occurred_at' => $event->occurred_at,
            ]);

        return response()->json(['data' => $events]);
    }
}