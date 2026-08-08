<?php

namespace App\Repositories;

use App\Models\Source;
use App\Repositories\Contracts\SourceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SourceRepository implements SourceRepositoryInterface
{
    public function paginateByOrganization(int $organizationId, int $perPage = 15): LengthAwarePaginator
    {
        return Source::where('organization_id', $organizationId)
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Source
    {
        return Source::find($id);
    }

    public function create(array $data): Source
    {
        return Source::create($data);
    }

    public function update(Source $source, array $data): Source
    {
        $source->update($data);

        return $source->fresh();
    }
}