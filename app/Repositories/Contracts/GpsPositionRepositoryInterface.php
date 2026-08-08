<?php

namespace App\Repositories\Contracts;

use App\Models\GpsPosition;
use App\Models\Lot;
use Illuminate\Database\Eloquent\Collection;

interface GpsPositionRepositoryInterface
{
    public function create(array $data): GpsPosition;

    public function listForLot(Lot $lot): Collection;
}