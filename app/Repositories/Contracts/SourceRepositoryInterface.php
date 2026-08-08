<?php

namespace App\Repositories\Contracts;

use App\Models\Source;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SourceRepositoryInterface
{
    public function paginateByOrganization(int $organizationId, int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Source;

    public function create(array $data): Source;

    public function update(Source $source, array $data): Source;
}