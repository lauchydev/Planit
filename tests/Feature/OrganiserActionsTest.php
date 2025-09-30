<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganiserActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_organiser_can_log_in_and_view_their_specific_dashboard(): void
    {
        $organiser = User::factory()->create([
            'role' => 'organiser',
            'email' => 'organiser@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Test login
        $response = $this->post('/login', [
            'email' => 'organiser@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($organiser);

        // Test dashboard access
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('dashboard.index');
        $response->assertSee('Organiser Dashboard');
    }

    public function test_an_organiser_can_successfully_create_an_event_with_valid_data(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $this->actingAs($organiser);

        $eventData = [
            'title' => 'Test Event',
            'description' => 'This is a test event description',
            'start_time' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'end_time' => now()->addDays(7)->addHours(2)->format('Y-m-d\TH:i'),
            'location' => 'Test Location',
            'capacity' => 50,
        ];

        $response = $this->post('/events', $eventData);

        $this->assertDatabaseHas('events', [
            'title' => 'Test Event',
            'description' => 'This is a test event description',
            'location' => 'Test Location',
            'capacity' => 50,
            'organiser_id' => $organiser->id,
        ]);

        $event = Event::where('title', 'Test Event')->first();
        $response->assertRedirect("/events/{$event->id}");
    }

    public function test_an_organiser_receives_validation_errors_for_invalid_event_data(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $this->actingAs($organiser);

        // Test missing required fields
        $response = $this->post('/events', []);

        $response->assertSessionHasErrors(['title', 'start_time', 'end_time', 'location', 'capacity']);

        // Test invalid data types and constraints
        $invalidData = [
            'title' => str_repeat('a', 101), // Too long (max 100)
            'start_time' => now()->subDays(1)->format('Y-m-d\TH:i'), // Past date
            'end_time' => now()->subDays(1)->format('Y-m-d\TH:i'),
            'location' => str_repeat('a', 256), // Too long (max 255)
            'capacity' => 0, // Below minimum (min 1)
        ];

        $response = $this->post('/events', $invalidData);
        $response->assertSessionHasErrors(['title', 'start_time', 'location', 'capacity']);
    }

    public function test_an_organiser_can_successfully_update_an_event_they_own(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $event = Event::factory()->create(['organiser_id' => $organiser->id]);

        $this->actingAs($organiser);

        $updateData = [
            'title' => 'Updated Event Title',
            'description' => 'This is an updated description that meets the minimum requirement of 20 characters',
            'start_time' => now()->addDays(10)->format('Y-m-d\TH:i'),
            'end_time' => now()->addDays(10)->addHours(2)->format('Y-m-d\TH:i'),
            'location' => 'Updated Location',
            'capacity' => 100,
        ];

        $response = $this->put("/events/{$event->id}", $updateData);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Updated Event Title',
            'description' => 'This is an updated description that meets the minimum requirement of 20 characters',
            'location' => 'Updated Location',
            'capacity' => 100,
        ]);

        $response->assertRedirect("/events/{$event->id}");
    }

    public function test_an_organiser_cannot_update_an_event_created_by_another_organiser(): void
    {
        $organiser1 = User::factory()->create(['role' => 'organiser']);
        $organiser2 = User::factory()->create(['role' => 'organiser']);
        $event = Event::factory()->create(['organiser_id' => $organiser1->id]);

        $this->actingAs($organiser2);

        $updateData = [
            'title' => 'Unauthorized Update',
            'description' => 'This should not work',
            'start_time' => now()->addDays(10)->format('Y-m-d\TH:i'),
            'end_time' => now()->addDays(10)->addHours(2)->format('Y-m-d\TH:i'),
            'location' => 'Unauthorized Location',
            'capacity' => 50,
        ];

        $response = $this->put("/events/{$event->id}", $updateData);

        $response->assertStatus(403); // Forbidden
        
        // Verify the event was not updated
        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
            'title' => 'Unauthorized Update',
        ]);
    }

    public function test_an_organiser_can_delete_an_event_they_own_that_has_no_bookings(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $event = Event::factory()->create(['organiser_id' => $organiser->id]);

        $this->actingAs($organiser);

        $response = $this->delete("/events/{$event->id}");

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    public function test_an_organiser_cannot_delete_an_event_that_has_active_bookings(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create(['organiser_id' => $organiser->id]);

        // Create a booking for the event
        Booking::create([
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'booked_at' => now(),
        ]);

        $this->actingAs($organiser);

        $response = $this->delete("/events/{$event->id}");

        $response->assertStatus(403);
        
        // Verify the event was not deleted
        $this->assertDatabaseHas('events', ['id' => $event->id]);
    }
}
