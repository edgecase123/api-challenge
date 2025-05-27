<?php

namespace Tests\Feature\Api;

use App\Models\Search;
use App\Models\SearchList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SearchListTest extends TestCase
{
    const SEARCHLIST_URL = '/api/v1/searchlist';

    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
        $this->seed();
    }

    #[Test]
    public function searchListsCanHaveSearchChildren(): void
    {
        $badGuys = SearchList::whereName('Bad Guys')->first();
        $this->assertNotNull($badGuys);
        assert($badGuys instanceof SearchList);

        $count = $badGuys->searches()->count();
        $sauronSearch = Search::make(['term' => 'Sauron', 'field' => 'name']);
        $badGuys->searches()->save($sauronSearch);
        $this->assertCount($count + 1, $badGuys->searches()->newQuery()->get());

        $count = $badGuys->searches()->count();
        $sarumanSearch = Search::make(['term' => 'Saruman', 'field' => 'name']);
        $badGuys->searches()->save($sarumanSearch);
        $this->assertCount($count + 1, $badGuys->searches()->newQuery()->get());
    }

    #[Test]
    public function itRetrievesSearchLists(): void
    {
        $response = $this->getJson(self::SEARCHLIST_URL);

        Log::log('debug', __FUNCTION__ . ': ' . 'Results from searchlist', [$response->getContent()]);

        $response->assertOk();
    }

    #[Test]
    public function itRetrievesSearchesFromSearchList(): void
    {
        $testUser = User::whereName('Test User')->first();
        assert($testUser instanceof User);

        $badGuys = $testUser->searchLists()->where('name', 'Bad Guys')->first();
        assert($badGuys instanceof SearchList);

        $newSearch = Search::make([
            'term' => 'tree',
            'field' => 'name',
        ]);

        $badGuys->searches()->save($newSearch);
        $response = $this->getJson(self::SEARCHLIST_URL . "/$badGuys->id/searches");

        $response->assertOk();
        $result = $response->json();
        Log::log('debug', __FUNCTION__ . ': ' . 'Result', [$result]);
    }

    #[Test]
    public function itDeletesSearchList(): void
    {
        $testUser = User::whereName('Test User')->first();
        assert($testUser instanceof User);

        $badGuys = $testUser->searchLists()->where('name', 'Bad Guys')->first();
        assert($badGuys instanceof SearchList);

        $response = $this->deleteJson(self::SEARCHLIST_URL . "/$badGuys->id");

        $response->assertOk();
        $this->assertDatabaseMissing(SearchList::class, ['id' => $badGuys->id]);
    }

    #[Test]
    public function itCreatesSearchList(): void
    {
        $testUser = User::whereName('Test User')->first();
        assert($testUser instanceof User);

        $badGuys = $testUser->searchLists()->where('name', 'Bad Guys')->first();
        assert($badGuys instanceof SearchList);

        $data = [
            'name' => 'Small People',
        ];

        $response = $this->postJson(self::SEARCHLIST_URL, $data);

        $response->assertOk();

        $this->assertDatabaseHas(SearchList::class, [
            'name' => 'Small People',
            'user_id' => $testUser->id,
        ]);

        Log::log('debug', __FUNCTION__ . ': ' . 'Response from create search', [$response->getContent()]);
    }

    #[Test]
    public function itDeletesSavedSearch(): void
    {
        $testUser = User::whereName('Test User')->first();
        assert($testUser instanceof User);

        $grimaWormtoungue = Search::whereTerm('Grima')->first();
        $this->assertNotNull($grimaWormtoungue);

        $list = $grimaWormtoungue->searchList()->first();
        $this->assertNotNull($list);

        $url = self::SEARCHLIST_URL . "/$list->id/searches/$grimaWormtoungue->id";
        $response = $this->deleteJson($url);

        $response->assertOk();
    }

    #[Test]
    public function itSavesNewSearch(): void
    {
        $testUser = User::whereName('Test User')->first();
        assert($testUser instanceof User);

        $badGuys = $testUser->searchLists()->where('name', 'Bad Guys')->first();
        assert($badGuys instanceof SearchList);

        $postData = [
            'term' => 'orc',
            'field' => 'race',
        ];

        $url = self::SEARCHLIST_URL . "/$badGuys->id/searches";
        $response = $this->postJson($url, $postData);
        $response->assertOk();

        $this->assertDatabaseHas(Search::class, [
            'term' => 'orc',
            'field' => 'race',
            'search_list_id' => $badGuys->id,
        ]);

        // Attempt duplicate, expect to fail
        $response = $this->postJson($url, $postData);
        $response->assertStatus(409);
    }
}
