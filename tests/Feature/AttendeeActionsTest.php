<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendeeActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_successfully_register_as_an_attendee(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'agree_privacy' => true,
        ];

        $response = $this->post('/register', $userData);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'attendee',
        ]);
    }

    public function test_a_registered_attendee_can_log_in_and_log_out(): void
    {
        $attendee = User::factory()->create([
            'role' => 'attendee',
            'email' => 'attendee@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Test login
        $response = $this->post('/login', [
            'email' => 'attendee@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($attendee);

        // Test logout
        $response = $this->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_a_logged_in_attendee_can_book_an_available_upcoming_event(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $attendee = User::factory()->create(['role' => 'attendee']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'capacity' => 10,
            'start_time' => now()->addDays(7),
        ]);

        $this->actingAs($attendee);

        $response = $this->post("/events/{$event->id}/book");

        $response->assertRedirect("/events/{$event->id}");
        $this->assertDatabaseHas('bookings', [
            'user_id' => $attendee->id,
            'event_id' => $event->id,
        ]);
    }

    public function test_after_booking_an_attendee_can_see_the_event_on_their_bookings_page(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $attendee = User::factory()->create(['role' => 'attendee']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Booked Event',
            'start_time' => now()->addDays(7),
        ]);

        Booking::create([
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'booked_at' => now(),
        ]);

        $this->actingAs($attendee);

        $response = $this->get('/bookings');

        $response->assertStatus(200);
        $response->assertViewIs('bookings.index');
        $response->assertSee('Booked Event');
    }

    public function test_an_attendee_cannot_book_the_same_event_more_than_once(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $attendee = User::factory()->create(['role' => 'attendee']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'capacity' => 10,
            'start_time' => now()->addDays(7),
        ]);

        // First booking
        Booking::create([
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'booked_at' => now(),
        ]);

        $this->actingAs($attendee);

        // Try to book again
        $response = $this->post("/events/{$event->id}/book");

        $response->assertRedirect("/events/{$event->id}");
        $response->assertSessionHas('error');
        
        // Should still only have one booking
        $this->assertEquals(1, Booking::where('user_id', $attendee->id)
            ->where('event_id', $event->id)->count());
    }

    public function test_an_attendee_cannot_book_a_full_event(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $attendee = User::factory()->create(['role' => 'attendee']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'capacity' => 2,
            'start_time' => now()->addDays(7),
        ]);

        // Fill the event to capacity
        $otherAttendees = User::factory()->count(2)->create(['role' => 'attendee']);
        foreach ($otherAttendees as $otherAttendee) {
            Booking::create([
                'user_id' => $otherAttendee->id,
                'event_id' => $event->id,
                'booked_at' => now(),
            ]);
        }

        $this->actingAs($attendee);

        // Try to book the full event
        $response = $this->post("/events/{$event->id}/book");

        $response->assertRedirect("/events/{$event->id}");
        $response->assertSessionHas('error');
        
        // Should not create a booking
        $this->assertDatabaseMissing('bookings', [
            'user_id' => $attendee->id,
            'event_id' => $event->id,
        ]);
    }

    public function test_an_attendee_cannot_see_edit_or_delete_buttons_on_any_event_page(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $attendee = User::factory()->create(['role' => 'attendee']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'start_time' => now()->addDays(7),
        ]);

        $this->actingAs($attendee);

        $response = $this->get("/events/{$event->id}");

        $response->assertStatus(200);
        $response->assertDontSee('Edit');
        $response->assertDontSee('Delete');
    }

    public function test_attendee_cannot_access_dashboard(): void
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $this->actingAs($attendee);

        $this->get('/dashboard')->assertStatus(403);
    }
}
