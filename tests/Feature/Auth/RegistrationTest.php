<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'nama' => 'Test User',
            'nomor_hp' => '08123456789',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'warga',
        ]);

        $this->assertDatabaseHas('warga', [
            'nama' => 'Test User',
            'nomor_hp' => '08123456789',
        ]);

        $this->assertSame('warga', User::where('email', 'test@example.com')->value('role'));
    }
}
