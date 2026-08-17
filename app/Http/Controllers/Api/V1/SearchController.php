<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(protected SearchService $searchService)
    {
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->query('q', '');

        if (strlen($query) < 2) {
            return response()->json(['data' => []]);
        }

        $lots = $this->searchService->searchLots($query, $request->user());

        $results = $lots->map(fn ($lot) => [
            'id' => $lot->id,
            'uuid' => $lot->uuid,
            'status' => $lot->status,
            'resource_name' => $lot->resourceType?->name,
            'passport_identifier' => $lot->passport?->unique_identifier,
        ]);

        return response()->json(['data' => $results]);
    }
}