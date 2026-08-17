<?php

namespace App\Repositories\Contracts;

use App\Models\Document;
use Illuminate\Database\Eloquent\Collection;

interface DocumentRepositoryInterface
{
    public function listForLot(int $lotId): Collection;

    public function create(array $data): Document;
}