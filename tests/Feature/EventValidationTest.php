<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class EventValidationTest extends TestCase
{
    use RefreshDatabase;

    private function organiser() {
        return User::factory()->create(['role' => 'organiser']);
    }

    #[Test]
    public function organiser_can_create_event_with_minimum_fields(): void
    {
        $organiser = $this->organiser();

        $payload = [
            'title' => 'Short Title',
            // description omitted intentionally (nullable)
            'start_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'end_time' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'location' => 'Somewhere',
            'capacity' => 25,
        ];

        $response = $this->actingAs($organiser)->post(route('events.store'), $payload);
        $response->assertRedirect();
        $this->assertDatabaseHas('events', [
            'title' => 'Short Title',
            'location' => 'Somewhere',
            'capacity' => 25,
        ]);
    }

    #[Test]
    public function description_must_be_20_chars_if_present(): void
    {
        $organiser = $this->organiser();

        $payload = [
            'title' => 'Title',
            'description' => 'Too short',
            'start_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'end_time' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'location' => 'Place',
            'capacity' => 10,
        ];

        $response = $this->actingAs($organiser)->post(route('events.store'), $payload);
        $response->assertSessionHasErrors('description');
    }

    #[Test]
    public function cannot_set_capacity_below_existing_bookings(): void
    {
        $organiser = $this->organiser();
        $event = Event::factory()->create(['organiser_id' => $organiser->id, 'capacity' => 5]);
        // simulate bookings (direct factory usage)
        \App\Models\Booking::factory(3)->create(['event_id' => $event->id]);

        $payload = [
            'title' => $event->title,
            'description' => $event->description,
            'start_time' => $event->start_time->format('Y-m-d H:i:s'),
            'end_time' => $event->end_time->format('Y-m-d H:i:s'),
            'location' => $event->location,
            'capacity' => 2, // below 3 bookings
        ];

        $response = $this->actingAs($organiser)->put(route('events.update', $event), $payload);
        $response->assertSessionHasErrors('capacity');
    }

    #[Test]
    public function cannot_move_future_event_start_back_in_time_before_now(): void
    {
        $organiser = $this->organiser();
        $event = Event::factory()->create(['organiser_id' => $organiser->id, 'start_time' => now()->addDays(5), 'end_time' => now()->addDays(6)]);

        $payload = [
            'title' => $event->title,
            'description' => $event->description,
            'start_time' => now()->subDay()->format('Y-m-d H:i:s'), // attempt to move before now
            'end_time' => $event->end_time->format('Y-m-d H:i:s'),
            'location' => $event->location,
            'capacity' => $event->capacity,
        ];

        $response = $this->actingAs($organiser)->put(route('events.update', $event), $payload);
        $response->assertSessionHasErrors('start_time');
    }
}
