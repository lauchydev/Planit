<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganiserActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_organiser_can_view_dashboard(): void
    {
        $organiser = User::factory()->organiser()->create();
        Event::factory()->for($organiser, 'organiser')->create();

        $this->actingAs($organiser);
        $response = $this->get('/dashboard');
        $response->assertOk();
        $response->assertSee('Organiser Dashboard');
    }

    public function test_dashboard_metrics_match_eloquent(): void
    {
        $organiser = User::factory()->organiser()->create();

        // Event A: future, capacity 5, 3 bookings -> Upcoming, remaining 2, fullness 60.0%
        $eventA = Event::factory()->for($organiser, 'organiser')->create([
            'capacity' => 5,
            'start_time' => now()->addDays(3),
            'end_time' => now()->addDays(3)->addHours(2),
        ]);

        // Event B: future, capacity 2, 2 bookings -> Full, remaining 0, fullness 100.0%
        $eventB = Event::factory()->for($organiser, 'organiser')->create([
            'capacity' => 2,
            'start_time' => now()->addDays(1),
            'end_time' => now()->addDays(1)->addHours(1),
        ]);

        // Event C: past, capacity 4, 1 booking -> Past, remaining 3, fullness 25.0%
        $eventC = Event::factory()->for($organiser, 'organiser')->create([
            'capacity' => 4,
            'start_time' => now()->subDays(2),
            'end_time' => now()->subDays(2)->addHours(1),
        ]);

        // Seed bookings (unique users auto-created by factory)
        \App\Models\Booking::factory()->count(3)->create(['event_id' => $eventA->id]);
        \App\Models\Booking::factory()->count(2)->create(['event_id' => $eventB->id]);
        \App\Models\Booking::factory()->count(1)->create(['event_id' => $eventC->id]);

        // Query raw SQL results
        $rows = \App\Queries\OrganiserDashboardQuery::forUser($organiser->id);
        $byId = collect($rows)->keyBy('event_id');

        // Helper to assert a single event row
        $assertRow = function (Event $event, string $expectedStatus) use ($byId) {
            $row = $byId->get($event->id);
            $this->assertNotNull($row, 'Missing row for event '.$event->id);

            $bookings = $event->bookings()->count();
            $remaining = $event->capacity - $bookings;
            $expectedFullness = $event->capacity > 0 ? round(($bookings * 100.0) / $event->capacity, 1) : 0.0;

            $this->assertSame($event->title, $row->title);
            $this->assertSame($event->capacity, (int) $row->capacity);
            $this->assertSame($bookings, (int) $row->bookings_count);
            $this->assertSame($remaining, (int) $row->remaining);
            $this->assertEqualsWithDelta($expectedFullness, (float) $row->fullness_percent, 0.05);
            $this->assertSame($expectedStatus, (string) $row->status);
        };

        $assertRow($eventA, 'Upcoming');
        $assertRow($eventB, 'Full');
        $assertRow($eventC, 'Past');
    }
}
