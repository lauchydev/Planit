<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class BookingCancellationTest extends TestCase
{
    use RefreshDatabase;

    private function attendee()
    {
        return User::factory()->create(['role' => 'attendee']);
    }

    private function organiser()
    {
        return User::factory()->create(['role' => 'organiser']);
    }

    #[Test]
    public function attendee_can_cancel_own_future_booking(): void
    {
        $user = $this->attendee();
        $event = Event::factory()->create(['start_time' => now()->addDays(2), 'end_time' => now()->addDays(2)->addHours(3)]);
        $booking = Booking::factory()->create(['user_id' => $user->id, 'event_id' => $event->id]);

        $response = $this->actingAs($user)->delete(route('events.bookings.delete', [$event, $booking]));

        $response->assertRedirect(route('events.details', $event));
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    #[Test]
    public function attendee_cannot_cancel_after_event_start(): void
    {
        $user = $this->attendee();
        $event = Event::factory()->past()->create(['end_time' => now()->subHours(1)]); // already started (and ended)
        $booking = Booking::factory()->create(['user_id' => $user->id, 'event_id' => $event->id]);

        $response = $this->actingAs($user)->delete(route('events.bookings.delete', [$event, $booking]));
        $response->assertRedirect(route('events.details', $event));
        $this->assertDatabaseHas('bookings', ['id' => $booking->id]);
    }

    #[Test]
    public function attendee_cannot_cancel_another_users_booking(): void
    {
        $user = $this->attendee();
        $other = $this->attendee();
        $event = Event::factory()->create(['start_time' => now()->addDay(), 'end_time' => now()->addDay()->addHours(2)]);
        $booking = Booking::factory()->create(['user_id' => $other->id, 'event_id' => $event->id]);

        $response = $this->actingAs($user)->delete(route('events.bookings.delete', [$event, $booking]));

        $response->assertRedirect(route('events.details', $event));
        $this->assertDatabaseHas('bookings', ['id' => $booking->id]);
    }

    #[Test]
    public function guest_cannot_cancel_booking(): void
    {
        $event = Event::factory()->create(['start_time' => now()->addDay(), 'end_time' => now()->addDay()->addHours(2)]);
        $booking = Booking::factory()->create(['event_id' => $event->id]);

        $response = $this->delete(route('events.bookings.delete', [$event, $booking]));
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('bookings', ['id' => $booking->id]);
    }
}
