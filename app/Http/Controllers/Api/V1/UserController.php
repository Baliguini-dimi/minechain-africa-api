<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserManagementService $userManagementService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $users = $this->userManagementService->listByOrganization(
            $request->user()->organization_id
        );

        return response()->json(['data' => UserResource::collection($users)]);
    }

    public function show(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        return response()->json(['data' => new UserResource($user)]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userManagementService->invite(
            $request->validated(),
            $request->user()
        );

        return response()->json(new UserResource($user), 201);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $updated = $this->userManagementService->update(
            $user,
            $request->validated(),
            $request->user()
        );

        return response()->json(new UserResource($updated));
    }

    public function suspend(Request $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $updated = $this->userManagementService->suspend($user, $request->user());

        return response()->json(new UserResource($updated));
    }

    public function reactivate(Request $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $updated = $this->userManagementService->reactivate($user, $request->user());

        return response()->json(new UserResource($updated));
    }
}