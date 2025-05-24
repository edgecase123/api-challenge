<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\AbstractController;
use App\Services\TheOneApiService;
use Illuminate\Http\JsonResponse;

class TheOneController extends AbstractController
{
    public function index(TheOneApiService $oneApiService): JsonResponse
    {
        $params = $this->validateInput([
            'term' => 'sometimes|regex:/^[a-zA-Z0-9\s]+$/|min:3',
            'limit' => 'sometimes|int|min:10|max:100',
        ]);

        $response = $oneApiService->getCharacters(
            $params['limit'] ?? 100,
            $params['term'] ?? null
        );

        return response()->json($response ?? ['count' => 0, 'data' => []]);
    }
}
