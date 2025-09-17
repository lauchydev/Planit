<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */

    public function run(): void
    {
        DB::table('users')->insert([
            'name' => "Bob",
            'email' => "bob@email.com",
            'password' => bcrypt('123456'),
        ]);
        DB::table('users')->insert([
            'name' => "Fred",
            'email' => "Fred@email.com",
            'password' => bcrypt('123456'),
        ]);
    }
}
