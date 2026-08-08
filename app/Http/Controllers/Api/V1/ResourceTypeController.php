<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResourceTypeRequest;
use App\Http\Requests\UpdateResourceTypeRequest;
use App\Http\Resources\ResourceTypeResource;
use App\Models\ResourceType;
use App\Services\ResourceTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResourceTypeController extends Controller
{
    public function __construct(protected ResourceTypeService $resourceTypeService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', ResourceType::class);

        $types = $this->resourceTypeService->listAvailableFor($request->user());

        return response()->json(ResourceTypeResource::collection($types));
    }

    public function show(ResourceType $resourceType): JsonResponse
    {
        $this->authorize('view', $resourceType);

        return response()->json(new ResourceTypeResource($resourceType));
    }

    public function store(StoreResourceTypeRequest $request): JsonResponse
    {
        $type = $this->resourceTypeService->create($request->validated(), $request->user());

        return response()->json(new ResourceTypeResource($type), 201);
    }

    public function update(UpdateResourceTypeRequest $request, ResourceType $resourceType): JsonResponse
    {
        $updated = $this->resourceTypeService->update($resourceType, $request->validated(), $request->user());

        return response()->json(new ResourceTypeResource($updated));
    }
}