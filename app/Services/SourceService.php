<?php

namespace App\Services;

use App\Models\Source;
use App\Models\User;
use App\Repositories\Contracts\SourceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SourceService
{
    public function __construct(
        protected SourceRepositoryInterface $sourceRepository
    ) {
    }

    public function listByOrganization(int $organizationId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->sourceRepository->paginateByOrganization($organizationId, $perPage);
    }

    public function find(int $id): ?Source
    {
        return $this->sourceRepository->findById($id);
    }

    public function create(array $data, User $actor): Source
    {
        return DB::transaction(function () use ($data, $actor) {
            $source = $this->sourceRepository->create([
                ...$data,
                'organization_id' => $actor->organization_id,
                'status' => $data['status'] ?? 'active',
            ]);

            $this->logAudit($actor, 'create', $source);

            return $source;
        });
    }

    public function update(Source $source, array $data, User $actor): Source
    {
        return DB::transaction(function () use ($source, $data, $actor) {
            $oldValues = $source->only(array_keys($data));

            $updated = $this->sourceRepository->update($source, $data);

            $this->logAudit($actor, 'update', $updated, $oldValues, $data);

            return $updated;
        });
    }

    protected function logAudit(User $actor, string $action, Source $source, array $oldValue = [], array $newValue = []): void
    {
        $actor->auditLogs()->create([
            'action' => $action,
            'entity_type' => Source::class,
            'entity_id' => $source->id,
            'old_value' => $oldValue ?: null,
            'new_value' => $newValue ?: $source->toArray(),
            'occurred_at' => now(),
        ]);
    }
}