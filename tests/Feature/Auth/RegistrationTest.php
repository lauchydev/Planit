<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'agree_privacy' => true, // Updated to match your form field name
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
        
        // Verify user is created as attendee by default
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'attendee',
        ]);
    }

    public function test_user_cannot_register_without_agreeing_to_privacy_policy(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Test User',
            'email' => 'no-consent@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            // privacy_policy omitted - should fail validation
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['agree_privacy']);
        
        // Verify user was not created
        $this->assertDatabaseMissing('users', [
            'email' => 'no-consent@example.com',
        ]);
    }
}
