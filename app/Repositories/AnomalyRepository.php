<?php

namespace App\Repositories;

use App\Models\Anomaly;
use App\Repositories\Contracts\AnomalyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AnomalyRepository implements AnomalyRepositoryInterface
{
    public function create(array $data): Anomaly
    {
        return Anomaly::create($data);
    }

    public function findById(int $id): ?Anomaly
    {
        return Anomaly::find($id);
    }

    public function listOpenForOrganization(int $organizationId, int $limit = 10): Collection
    {
        return Anomaly::whereHas('lot', fn ($q) => $q->where('organization_id', $organizationId))
            ->where('status', 'open')
            ->with('lot')
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    public function update(Anomaly $anomaly, array $data): Anomaly
    {
        $anomaly->update($data);

        return $anomaly->fresh();
    }
}
