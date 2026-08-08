<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSourceRequest;
use App\Http\Requests\UpdateSourceRequest;
use App\Http\Resources\SourceResource;
use App\Models\Source;
use App\Services\SourceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    public function __construct(protected SourceService $sourceService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Source::class);

        $sources = $this->sourceService->listByOrganization($request->user()->organization_id);

        return response()->json(SourceResource::collection($sources));
    }

    public function show(Source $source): JsonResponse
    {
        $this->authorize('view', $source);

        return response()->json(new SourceResource($source));
    }

    public function store(StoreSourceRequest $request): JsonResponse
    {
        $source = $this->sourceService->create($request->validated(), $request->user());

        return response()->json(new SourceResource($source), 201);
    }

    public function update(UpdateSourceRequest $request, Source $source): JsonResponse
    {
        $updated = $this->sourceService->update($source, $request->validated(), $request->user());

        return response()->json(new SourceResource($updated));
    }
}