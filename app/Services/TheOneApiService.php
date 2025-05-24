<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service class with helper functions to interact with TheOneApi
 */
class TheOneApiService
{
    private string $apiKey;
    private string $apiUrl;

    public function __construct()
    {
        $this->apiKey = \config('services.theone.api_key');
        $this->apiUrl = \config('services.theone.base_url');
    }

    public function getCharacters(
        int    $limit = 100,
        string $term = null,
        string $field = null
    ): array|null
    {
        try {
            $url = "{$this->apiUrl}/character?limit=$limit";

            if ($term) {

                if (!$field) {
                    throw new \RuntimeException('field parameter is required with term is provided');
                }

                $url .= "&$field=/$term/i"; // Regex search
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                $characters = $data['docs'] ?? [];
                $charCount = count($characters);

                return [
                    'count' => $charCount,
                    'data' => $characters,
                ];
            } else {
                Log::log('debug', __FUNCTION__ . ': ' . 'Response from LoR:', [$response->body()]);
            }

            return null;
        } catch (\Exception $exception) {
            Log::log('error', __FUNCTION__ . ': ' . $exception->getMessage());

            return null;
        }
    }
}
