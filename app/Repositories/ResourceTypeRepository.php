<?php

namespace App\Repositories;

use App\Models\ResourceType;
use App\Repositories\Contracts\ResourceTypeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ResourceTypeRepository implements ResourceTypeRepositoryInterface
{
    public function listAvailableForOrganization(?int $organizationId): Collection
    {
        $query = ResourceType::query();

        if ($organizationId === null) {
            $query->whereNull('organization_id');
        } else {
            $query->where(function ($q) use ($organizationId) {
                $q->whereNull('organization_id')
                    ->orWhere('organization_id', $organizationId);
            });
        }

        return $query->orderBy('name')->get();
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