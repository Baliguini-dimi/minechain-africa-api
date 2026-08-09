<?php

namespace App\Repositories\Contracts;

use App\Models\Lot;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LotRepositoryInterface
{
    public function paginateByOrganization(?int $organizationId, int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Lot;

    public function create(array $data): Lot;

    public function update(Lot $lot, array $data): Lot;
}