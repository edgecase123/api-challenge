<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\AbstractController;
use App\Services\TheOneApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TheOneController extends AbstractController
{
    public function index(TheOneApiService $oneApiService): JsonResponse
    {
        $params = $this->validateInput([
            'field' => 'regex:/^[a-z]+$/|min:3|required_with:term|in:name,race,birth,death',
            'term' => 'regex:/^[a-zA-Z0-9\s]+$/|min:3|required_with:field',
            'limit' => 'sometimes|int|min:10|max:100',
        ]);

        $response = $oneApiService->getCharacters(
            $params['limit'] ?? 100,
            $params['term'] ?? null,
            $params['field'] ?? null,
        );

        return response()->json($response ?? ['count' => 0, 'data' => []]);
    }
}
