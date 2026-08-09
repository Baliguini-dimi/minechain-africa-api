<?php

namespace App\Repositories;

use App\Models\Source;
use App\Repositories\Contracts\SourceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SourceRepository implements SourceRepositoryInterface
{
    public function paginateByOrganization(?int $organizationId, int $perPage = 15): LengthAwarePaginator
    {
        $query = Source::orderBy('name');

        if ($organizationId !== null) {
            $query->where('organization_id', $organizationId);
        }

        return $query->paginate($perPage);
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