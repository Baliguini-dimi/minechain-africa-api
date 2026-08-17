<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppNotificationResource;
use App\Models\AppNotification;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $notifications = AppNotification::where('user_id', $request->user()->id)
            ->latest()
            ->limit(20)
            ->get();

        return response()->json(['data' => AppNotificationResource::collection($notifications)]);
    }

    public function markAsRead(Request $request, AppNotification $notification): JsonResponse
    {
        $this->authorize('update', $notification);

        $updated = $this->notificationService->markAsRead($notification);

        return response()->json(new AppNotificationResource($updated));
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $this->notificationService->markAllAsRead($request->user());

        return response()->json(['message' => 'Toutes les notifications ont été marquées comme lues.']);
    }
}