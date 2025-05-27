<?php

namespace Database\Seeders;

use App\Models\Search;
use App\Models\SearchList;
use App\Models\User;
use Illuminate\Database\Seeder;

class SearchListSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::whereName('Test User')->first();

        $badBoys = SearchList::factory()->make([
            'name' => 'Bad Guys'
        ]);

        $user->searchLists()->save($badBoys);

        $badBoys->searches()->save(Search::make([
            'term' => 'Sauron',
            'field' => 'name',
        ]));

        $badBoys->searches()->save(Search::make([
            'term' => 'Grima',
            'field' => 'name',
        ]));

        $badBoys->searches()->save(Search::make([
            'term' => 'Saruman',
            'field' => 'name',
        ]));

        $badBoys->searches()->save(Search::make([
            'term' => 'Gollum',
            'field' => 'name',
        ]));


        $user->searchLists()->save(SearchList::factory()->make([
            'name' => 'Good Guys'
        ]));
    }
}
