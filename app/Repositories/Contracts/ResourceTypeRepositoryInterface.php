<?php

namespace App\Repositories\Contracts;

use App\Models\ResourceType;
use Illuminate\Database\Eloquent\Collection;

interface ResourceTypeRepositoryInterface
{
    /**
     * Retourne les types globaux (organization_id null) + ceux propres à l'organisation.
     */
    public function listAvailableForOrganization(int $organizationId): Collection;

    public function findById(int $id): ?ResourceType;

    public function create(array $data): ResourceType;

    public function update(ResourceType $resourceType, array $data): ResourceType;
}