<?php

namespace Database\Seeders;

use App\Models\SearchList;
use App\Models\User;
use Illuminate\Database\Seeder;

class SearchListSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::whereName('Test User')->first();

        $user->searchLists()->save(SearchList::factory()->make([
            'name' => 'Bad Guys'
        ]));

        $user->searchLists()->save(SearchList::factory()->make([
            'name' => 'Good Guys'
        ]));
    }
}
