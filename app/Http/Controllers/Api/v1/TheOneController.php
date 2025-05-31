<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\AbstractController;
use App\Services\TheOneApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TheOneController extends AbstractController
{
    private const DEFAULT_LIST_KEY = 'default_characters';

    public function index(TheOneApiService $oneApiService): JsonResponse
    {
        $params = $this->validateInput([
            'field' => 'regex:/^[a-z]+$/|min:3|required_with:term|in:name,race,birth,death',
            'term' => 'regex:/^[a-zA-Z0-9\s]+$/|min:3|required_with:field',
            'limit' => 'sometimes|int|min:10|max:100',
        ]);

        // Use cache for the default show all query to TheOneApi.
        // Otherwise, retrieve directly
        if (empty($params['term']) && empty($params['field'])) {
            $response = Cache::remember(self::DEFAULT_LIST_KEY, now()->addMinutes(10),
                function () use ($oneApiService) {
                    return $oneApiService->getCharacters();
                });
        } else {
            $response = $oneApiService->getCharacters(
                $params['limit'] ?? 100,
                $params['term'],
                $params['field'],
            );
        }

        return response()->json($response ?? ['count' => 0, 'data' => []]);
    }
}
