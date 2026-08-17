<?php

namespace App\Repositories;

use App\Models\Document;
use App\Models\Lot;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DocumentRepository implements DocumentRepositoryInterface
{
    public function listForLot(int $lotId): Collection
    {
        return Document::where('documentable_type', Lot::class)
            ->where('documentable_id', $lotId)
            ->latest()
            ->get();
    }

    public function create(array $data): Document
    {
        return Document::create($data);
    }
}