<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_can_view_the_paginated_list_of_upcoming_events(): void
    {
        // Create test events - some upcoming, some past
        $organiser = User::factory()->create(['role' => 'organiser']);
        
        // Create 10 upcoming events to test pagination (spec says max 8 per page)
        Event::factory()->count(10)->create([
            'organiser_id' => $organiser->id,
            'start_time' => now()->addDays(rand(1, 30)),
        ]);
        
        // Create past events that should not appear
        Event::factory()->count(3)->create([
            'organiser_id' => $organiser->id,
            'start_time' => now()->subDays(rand(1, 30)),
        ]);

        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertViewIs('events.index');
        $response->assertViewHas('events');
        
        // Should see pagination links since we have more than 8 events
        $response->assertSee('Next');
        
        // Should only see upcoming events (not past ones)
        $events = $response->viewData('events');
        $this->assertTrue($events->count() <= 8, 'Should display max 8 events per page');
        
        foreach ($events as $event) {
            $this->assertTrue($event->start_time > now(), 'Should only show upcoming events');
        }
    }

    public function test_a_guest_can_view_a_specific_event_details_page(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Test Event',
            'description' => 'Test Description',
            'location' => 'Test Location',
            'capacity' => 50,
            'start_time' => now()->addDays(7),
        ]);

        $response = $this->get("/events/{$event->id}");
        
        $response->assertStatus(200);
        $response->assertViewIs('events.details');
        $response->assertViewHas('event');
        $response->assertSee($event->title);
        $response->assertSee($event->description);
        $response->assertSee($event->location);
        $response->assertSee($organiser->name);
    }

    public function test_a_guest_is_redirected_when_accessing_protected_routes(): void
    {
        // Test organiser-only routes
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/events/create')->assertRedirect('/login');
        
        // Test attendee-only routes
        $this->get('/bookings')->assertRedirect('/login');
        
        // Test booking action requires auth
        $organiser = User::factory()->create(['role' => 'organiser']);
        $event = Event::factory()->create(['organiser_id' => $organiser->id]);
        
        $this->post("/events/{$event->id}/book")->assertRedirect('/login');
    }

    public function test_a_guest_cannot_see_action_buttons_on_event_details_page(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'start_time' => now()->addDays(7),
        ]);

        $response = $this->get("/events/{$event->id}");
        
        $response->assertStatus(200);
        
        // Should not see organiser buttons
        $response->assertDontSee('Edit');
        $response->assertDontSee('Delete');
        
        // Should not see attendee buttons
        $response->assertDontSee('Book Now');
        $response->assertDontSee('Cancel Booking');
    }
}
