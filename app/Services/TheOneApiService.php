<?php
declare(strict_types=1);

namespace App\Services;

/**
 * Service class with helper function to interact with TheOneApi
 */
class TheOneApiService
{
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = \config('theone.base_api_url');
    }


}
