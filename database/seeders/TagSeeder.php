<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Models\Event;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* Create a collection of tags */
        $names = ['Workshop', 'Lecture', 'Tutorial', 'Exam', 'Lab', 'Seminar'];
        $tags = collect($names)->map(fn($n) => Tag::firstOrCreate(['name' => $n]));

        /* Add tags to events */
        Event::query()->inRandomOrder()->take(10)->get()->each(function ($event) use ($tags) {
            $event->tags()->syncWithoutDetaching($tags->random(rand(1, min(3, $tags->count())))->pluck('id')->all());
        });
    }
}
