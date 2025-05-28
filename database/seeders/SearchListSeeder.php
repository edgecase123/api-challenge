<?php

namespace Database\Seeders;

use App\Models\Search;
use App\Models\SearchList;
use App\Models\User;
use Carbon\Carbon;
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
            'updated_at' => Carbon::now()->subDay(),
        ]));

        $badBoys->searches()->save(Search::make([
            'term' => 'Grima',
            'field' => 'name',
            'updated_at' => Carbon::now()->subHours(12),
        ]));

        $badBoys->searches()->save(Search::make([
            'term' => 'Saruman',
            'field' => 'name',
            'updated_at' => Carbon::now()->subMinutes(45),
        ]));

        $badBoys->searches()->save(Search::make([
            'term' => 'Gollum',
            'field' => 'name',
            'updated_at' => Carbon::now()->subMinutes(15),
        ]));


        $user->searchLists()->save(SearchList::factory()->make([
            'name' => 'Good Guys'
        ]));
    }
}
