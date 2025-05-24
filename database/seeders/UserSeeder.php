<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;

class UserSeeder extends Seeder
{
    use WithFaker;

    public function run(): void
    {
        $this->setUpFaker();
        User::factory()->count(3)->create();
    }
}
