<?php

namespace App\Repositories;

use App\Models\Anomaly;
use App\Repositories\Contracts\AnomalyRepositoryInterface;

class AnomalyRepository implements AnomalyRepositoryInterface
{
    public function create(array $data): Anomaly
    {
        return Anomaly::create($data);
    }

    public function findById(int $id): ?Anomaly
    {
        return Anomaly::find($id);
    }

    public function update(Anomaly $anomaly, array $data): Anomaly
    {
        $anomaly->update($data);

        return $anomaly->fresh();
    }
}