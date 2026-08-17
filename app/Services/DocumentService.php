<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Lot;
use App\Models\User;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function __construct(
        protected DocumentRepositoryInterface $documentRepository
    ) {
    }

    public function listForLot(int $lotId): Collection
    {
        return $this->documentRepository->listForLot($lotId);
    }

    public function upload(Lot $lot, UploadedFile $file, string $documentType, User $actor): Document
    {
        $path = $file->store('documents', 'public');

        return $this->documentRepository->create([
            'documentable_type' => Lot::class,
            'documentable_id' => $lot->id,
            'file_url' => Storage::url($path),
            'document_type' => $documentType,
            'uploaded_by' => $actor->id,
        ]);
    }
}