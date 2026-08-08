<?php

namespace App\Repositories;

use App\Models\CheckpointControl;
use App\Repositories\Contracts\CheckpointControlRepositoryInterface;

class CheckpointControlRepository implements CheckpointControlRepositoryInterface
{
    public function create(array $data): CheckpointControl
    {
        return CheckpointControl::create($data);
    }
}