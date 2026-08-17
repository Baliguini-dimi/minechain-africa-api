<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\Lot;
use App\Services\DocumentService;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{
    public function __construct(protected DocumentService $documentService)
    {
    }

    public function index(Lot $lot): JsonResponse
    {
        $this->authorize('viewAny', [\App\Models\Document::class, $lot]);

        $documents = $this->documentService->listForLot($lot->id);

        return response()->json(['data' => DocumentResource::collection($documents)]);
    }

    public function store(StoreDocumentRequest $request, Lot $lot): JsonResponse
    {
        $document = $this->documentService->upload(
            $lot,
            $request->file('file'),
            $request->validated('document_type'),
            $request->user()
        );

        return response()->json(new DocumentResource($document), 201);
    }
}