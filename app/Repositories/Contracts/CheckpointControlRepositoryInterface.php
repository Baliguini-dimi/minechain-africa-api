<?php

namespace App\Repositories\Contracts;

use App\Models\CheckpointControl;

interface CheckpointControlRepositoryInterface
{
    public function create(array $data): CheckpointControl;
}