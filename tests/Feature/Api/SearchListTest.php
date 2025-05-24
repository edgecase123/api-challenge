<?php

namespace Tests\Feature\Api;

use App\Models\Search;
use App\Models\SearchList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SearchListTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
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

        $sauronSearch = Search::make(['term' => 'Sauron', 'field' => 'name']);
        $badGuys->searches()->save($sauronSearch);
        $this->assertCount(1, $badGuys->searches()->newQuery()->get());

        $sarumanSearch = Search::make(['term' => 'Saruman', 'field' => 'name']);
        $badGuys->searches()->save($sarumanSearch);
        $this->assertCount(2, $badGuys->searches()->newQuery()->get());
    }
}
