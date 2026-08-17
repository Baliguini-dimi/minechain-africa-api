<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuditLogResource;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', AuditLog::class);

        $query = AuditLog::with('user')->latest('occurred_at');

        if (! $request->user()->hasRole('super_admin_technique')) {
            $organizationUserIds = User::where('organization_id', $request->user()->organization_id)->pluck('id');
            $query->whereIn('user_id', $organizationUserIds);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->query('action'));
        }

        if ($request->filled('entity_type')) {
            $query->where('entity_type', 'like', '%' . $request->query('entity_type') . '%');
        }

        $logs = $query->paginate(20);

        return response()->json(['data' => AuditLogResource::collection($logs)]);
    }
}