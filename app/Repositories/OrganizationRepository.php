<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrganizationRepository implements OrganizationRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Organization::orderBy('name')->paginate($perPage);
    }

    public function findById(int $id): ?Organization
    {
        return Organization::find($id);
    }

    public function create(array $data): Organization
    {
        return Organization::create($data);
    }

    public function update(Organization $organization, array $data): Organization
    {
        $organization->update($data);

        return $organization->fresh();
    }
}