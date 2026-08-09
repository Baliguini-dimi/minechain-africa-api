<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Services\OrganizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function __construct(protected OrganizationService $organizationService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Organization::class);

        $organizations = $this->organizationService->list();

        return response()->json(['data' => OrganizationResource::collection($organizations)]);
    }

    public function show(Organization $organization): JsonResponse
    {
        $this->authorize('view', $organization);

        return response()->json(['data' => new OrganizationResource($organization)]);
    }

    public function store(StoreOrganizationRequest $request): JsonResponse
    {
        $organization = $this->organizationService->create(
            $request->validated(),
            $request->user()
        );

        return response()->json(new OrganizationResource($organization), 201);
    }

    public function update(UpdateOrganizationRequest $request, Organization $organization): JsonResponse
    {
        $updated = $this->organizationService->update(
            $organization,
            $request->validated(),
            $request->user()
        );

        return response()->json(new OrganizationResource($updated));
    }
}