<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /*
     * Create a dummy Event with random data
     */
    public function definition(): array
    {
        /* Random date between now and 2 months from today */
        $startTime = fake()->dateTimeBetween('now', '+2 months');
        /* Event is 4 hours long */
        $endTime = fake()->dateTimeBetween($startTime, $startTime->format('Y-m-d H:i:s').' +4 hours');
        return [
            /* Random sentence with 3 words */
            'title' => fake()->sentence(3),
            /* Paragraph uses lorem ipsum placeholder text */
            'description' => fake()->paragraphs(3, true),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'location' => fake()->address(),
            /* Self explanatory */
            'capacity' => fake()->numberBetween(10, 100),
            'organiser_id' => User::factory()->organiser(),
        ];
    }

    /*
     * Creates an Event with dummy data set in the past (already happened)
     */
    public function past(): static {
        return $this->state(function (array $attributes) {
            /* Historic event date is between 2 months and a week ago */
            $startTime = fake()->dateTimeBetween('-2 months', '-1 week');
            $endTime = fake()->dateTimeBetween($startTime, $startTime->format('Y-m-d H:i:s').' +4 hours');

            return [
                'start_time' => $startTime,
                'end_time' => $endTime
            ];
        });
    }
}
