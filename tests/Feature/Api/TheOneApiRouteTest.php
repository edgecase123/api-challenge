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
            // no filter
            [20, 20, null, null, true],
            [100, 100, null, null, true],
            // name
            [20, 5, 'Gim', 'name', true],
            [20, 4, 'Lego', 'name', true],
            [20, 0, 'Harry Keogh', 'name', true],
            [20, 0, '[%&23002', 'name', false],
            // race
            [100, 1, 'Troll', 'race', true],
            [100, 99, 'Elf', 'race', true],
            [100, 100, 'Human ', 'race', true],
            [100, 47, 'Dwarf ', 'race', true],
            [100, 47, '[%&23002 ', 'race', false],
            // gender not allowed
            [100, 47, 'Male', 'gender', false],
            // min limit violation
            [1, 20, null, null, false],
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
        string|null $searchField,
        bool $succeeds,
    ): void
    {
        $params = ['limit' => $limit];

        if ($searchTerm) {
            $params['term'] = $searchTerm;
            $params['field'] = $searchField;
        }

        $url = self::URL . '?' . http_build_query($params);
        $response = $this->getJson($url);

        if ($succeeds === true) {
            $response->assertOk();
            $this->assertEquals($expectedCount, $response->json()['count']);
            $this->assertCount($expectedCount, $response->json()['data']);
        } else {
            $response->assertClientError();
        }
    }
}
