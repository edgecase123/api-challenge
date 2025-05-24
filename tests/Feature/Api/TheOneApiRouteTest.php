<?php

namespace Tests\Feature\Api;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TheOneApiRouteTest extends TestCase
{
    const LIMIT_100 = 'limit=100';
    const URL = '/api/v1/character';

    public static function dataItGetsCharacters(): array
    {
        return [
            [20, 20, null, true],
            [100, 100, null, true],
            [20, 5, 'Gim', true],
            [20, 4, 'Lego', true],
            [20, 0, 'Harry Keogh', true],
            [20, 0, '[%&23002', false],
            [1, 20, null, false],
        ];
    }

    /**
     * Tests calling the /character end point with various parameters
     * @param int $limit
     * @param int $expectedCount
     * @param string|null $searchTerm
     * @param bool $succeeds
     * @return void
     */
    #[Test]
    #[DataProvider('dataItGetsCharacters')]
    #[Group('api')]
    public function itGetsCharacters(
        int $limit,
        int $expectedCount,
        string|null $searchTerm,
        bool $succeeds,
    ): void
    {
        $params = ['limit' => $limit];

        if ($searchTerm) {
            $params['term'] = $searchTerm;
        }

        $url = self::URL . '?' . http_build_query($params);
        $response = $this->getJson($url);

        if ($succeeds) {
            $response->assertOk();
            $this->assertEquals($expectedCount, $response->json()['count']);
            $this->assertCount($expectedCount, $response->json()['data']);
        } else {
            $response->assertClientError();
        }
    }
}
