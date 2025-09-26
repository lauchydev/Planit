<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendeeActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendee_cannot_access_dashboard(): void
    {
        $attendee = User::factory()->attendee()->create();
        $this->actingAs($attendee);

        $this->get('/dashboard')->assertStatus(403);
    }
}
