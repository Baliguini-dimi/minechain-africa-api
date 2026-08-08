<?php

namespace App\Repositories;

use App\Models\Checkpoint;
use App\Repositories\Contracts\CheckpointRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CheckpointRepository implements CheckpointRepositoryInterface
{
    public function listAll(): Collection
    {
        return Checkpoint::orderBy('name')->get();
    }

    public function findById(int $id): ?Checkpoint
    {
        return Checkpoint::find($id);
    }
}