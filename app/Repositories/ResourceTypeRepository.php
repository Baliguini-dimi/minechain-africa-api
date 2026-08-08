<?php

namespace App\Repositories;

use App\Models\ResourceType;
use App\Repositories\Contracts\ResourceTypeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ResourceTypeRepository implements ResourceTypeRepositoryInterface
{
    public function listAvailableForOrganization(int $organizationId): Collection
    {
        return ResourceType::whereNull('organization_id')
            ->orWhere('organization_id', $organizationId)
            ->orderBy('name')
            ->get();
    }

    public function findById(int $id): ?ResourceType
    {
        return ResourceType::find($id);
    }

    public function create(array $data): ResourceType
    {
        return ResourceType::create($data);
    }

    public function update(ResourceType $resourceType, array $data): ResourceType
    {
        $resourceType->update($data);

        return $resourceType->fresh();
    }
}