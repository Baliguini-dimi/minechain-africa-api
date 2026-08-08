<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrganizationService
{
    public function __construct(
        protected OrganizationRepositoryInterface $organizationRepository
    ) {
    }

    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return $this->organizationRepository->paginate($perPage);
    }

    public function find(int $id): ?Organization
    {
        return $this->organizationRepository->findById($id);
    }

    public function create(array $data, User $actor): Organization
    {
        return DB::transaction(function () use ($data, $actor) {
            $organization = $this->organizationRepository->create([
                ...$data,
                'status' => 'pending_validation',
            ]);

            $this->logAudit($actor, 'create', $organization);

            return $organization;
        });
    }

    public function update(Organization $organization, array $data, User $actor): Organization
    {
        return DB::transaction(function () use ($organization, $data, $actor) {
            $oldValues = $organization->only(array_keys($data));

            $updated = $this->organizationRepository->update($organization, $data);

            $this->logAudit($actor, 'update', $updated, $oldValues, $data);

            return $updated;
        });
    }

    protected function logAudit(User $actor, string $action, Organization $organization, array $oldValue = [], array $newValue = []): void
    {
        $actor->auditLogs()->create([
            'action' => $action,
            'entity_type' => Organization::class,
            'entity_id' => $organization->id,
            'old_value' => $oldValue ?: null,
            'new_value' => $newValue ?: $organization->toArray(),
            'occurred_at' => now(),
        ]);
    }
}