<?php

namespace App\Services;

use App\Models\ResourceType;
use App\Models\User;
use App\Repositories\Contracts\ResourceTypeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ResourceTypeService
{
    public function __construct(
        protected ResourceTypeRepositoryInterface $resourceTypeRepository
    ) {
    }

    public function listAvailableFor(User $user): Collection
    {
        return $this->resourceTypeRepository->listAvailableForOrganization($user->organization_id);
    }

    public function find(int $id): ?ResourceType
    {
        return $this->resourceTypeRepository->findById($id);
    }

    public function create(array $data, User $actor): ResourceType
    {
        return DB::transaction(function () use ($data, $actor) {
            $resourceType = $this->resourceTypeRepository->create([
                ...$data,
                'organization_id' => $actor->organization_id,
            ]);

            $this->logAudit($actor, 'create', $resourceType);

            return $resourceType;
        });
    }

    public function update(ResourceType $resourceType, array $data, User $actor): ResourceType
    {
        return DB::transaction(function () use ($resourceType, $data, $actor) {
            $oldValues = $resourceType->only(array_keys($data));

            $updated = $this->resourceTypeRepository->update($resourceType, $data);

            $this->logAudit($actor, 'update', $updated, $oldValues, $data);

            return $updated;
        });
    }

    protected function logAudit(User $actor, string $action, ResourceType $resourceType, array $oldValue = [], array $newValue = []): void
    {
        $actor->auditLogs()->create([
            'action' => $action,
            'entity_type' => ResourceType::class,
            'entity_id' => $resourceType->id,
            'old_value' => $oldValue ?: null,
            'new_value' => $newValue ?: $resourceType->toArray(),
            'occurred_at' => now(),
        ]);
    }
}