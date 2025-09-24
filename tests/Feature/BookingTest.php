<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function attendee_can_book_an_available_future_event(): void
    {
        $user = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create(['capacity' => 5]);

        $response = $this->actingAs($user)->post(route('events.book', $event));

        $response->assertRedirect(route('events.show', $event));
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
    }

    #[Test]
    public function attendee_cannot_double_book(): void
    {
        $user = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create(['capacity' => 5]);
        Booking::factory()->create(['user_id' => $user->id, 'event_id' => $event->id]);

        $response = $this->actingAs($user)->post(route('events.book', $event));

        $response->assertRedirect(route('events.show', $event));
        $this->assertEquals(1, Booking::where('user_id', $user->id)->where('event_id', $event->id)->count());
    }

    #[Test]
    public function attendee_cannot_book_full_event(): void
    {
        $user = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create(['capacity' => 1]);
        // Fill event
        Booking::factory()->create(['event_id' => $event->id]);

        $response = $this->actingAs($user)->post(route('events.book', $event));

        $response->assertRedirect(route('events.show', $event));
        $this->assertEquals(1, Booking::where('event_id', $event->id)->count());
    }

    #[Test]
    public function attendee_cannot_book_started_event(): void
    {
        $user = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->past()->create(['capacity' => 5]);

        $response = $this->actingAs($user)->post(route('events.book', $event));

        $response->assertRedirect(route('events.show', $event));
        $this->assertDatabaseMissing('bookings', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
    }

    #[Test]
    public function organiser_cannot_book_events(): void
    {
        $user = User::factory()->create(['role' => 'organiser']);
        $event = Event::factory()->create(['capacity' => 5]);

        $response = $this->actingAs($user)->post(route('events.book', $event));

        $response->assertRedirect(route('events.show', $event));
        $this->assertDatabaseMissing('bookings', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
    }
}
