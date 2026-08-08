<?php

namespace App\Repositories\Contracts;

use App\Models\Checkpoint;
use Illuminate\Database\Eloquent\Collection;

interface CheckpointRepositoryInterface
{
    public function listAll(): Collection;

    public function findById(int $id): ?Checkpoint;
}