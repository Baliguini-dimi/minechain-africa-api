<?php

namespace App\Repositories;

use App\Models\Lot;
use App\Repositories\Contracts\LotRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LotRepository implements LotRepositoryInterface
{
    public function paginateByOrganization(int $organizationId, int $perPage = 15): LengthAwarePaginator
    {
        return Lot::where('organization_id', $organizationId)
            ->with(['source', 'resourceType', 'passport'])
            ->latest('creation_date')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Lot
    {
        return Lot::with(['source', 'resourceType', 'passport.events'])->find($id);
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