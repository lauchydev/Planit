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
}
