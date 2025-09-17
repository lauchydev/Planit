<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        /* Generate 10 Test Users */
        User::factory(10)->create();

        /* My own seeder for 'Bob' and 'Fred' */
        $this->call(UsersTableSeeder::class);
    }
}
