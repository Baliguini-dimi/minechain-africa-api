<?php

namespace App\Repositories;

use App\Models\GpsPosition;
use App\Models\Lot;
use App\Repositories\Contracts\GpsPositionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GpsPositionRepository implements GpsPositionRepositoryInterface
{
    public function create(array $data): GpsPosition
    {
        return GpsPosition::create($data);
    }

    public function listForLot(Lot $lot): Collection
    {
        return GpsPosition::where('lot_id', $lot->id)
            ->orderBy('recorded_at')
            ->get();
    }
}
