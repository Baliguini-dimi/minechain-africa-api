<?php

namespace App\Repositories;

use App\Models\Lot;
use App\Repositories\Contracts\LotRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LotRepository implements LotRepositoryInterface
{
    public function paginateByOrganization(?int $organizationId, int $perPage = 15): LengthAwarePaginator
    {
        $query = Lot::with(['source', 'resourceType', 'passport'])->latest('creation_date');

        if ($organizationId !== null) {
            $query->where('organization_id', $organizationId);
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?Lot
    {
        return Lot::with(['source', 'resourceType', 'passport.events', 'anomalies'])->find($id);
    }

    public function create(array $data): Lot
    {
        return Lot::create($data);
    }

    public function update(Lot $lot, array $data): Lot
    {
        $lot->update($data);

        return $lot->fresh();
    }
}