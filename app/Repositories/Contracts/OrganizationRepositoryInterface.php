<?php

namespace App\Repositories\Contracts;

use App\Models\Organization;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrganizationRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Organization;

    public function create(array $data): Organization;

    public function update(Organization $organization, array $data): Organization;
}