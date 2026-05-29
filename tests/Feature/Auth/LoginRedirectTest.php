<?php

namespace Tests\Feature\Auth;

use App\Models\Petugas;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_warga_login_redirects_to_warga_home(): void
    {
        $user = User::factory()->create([
            'email' => 'warga@example.com',
            'role' => 'warga',
        ]);

        Warga::create([
            'nama' => 'Warga Test',
            'nomor_hp' => '08123456789',
            'user_id' => $user->id,
        ]);

        $response = $this->post('/login', [
            'email' => 'warga@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('warga.home', absolute: false));
        $this->assertAuthenticatedAs($user);
    }

    public function test_petugas_login_redirects_to_petugas_home(): void
    {
        $user = User::factory()->create([
            'email' => 'petugas-login@example.com',
            'role' => 'petugas',
        ]);

        Petugas::create([
            'nip' => '198812122026011002',
            'nama' => 'Petugas Test',
            'nomor_hp' => '08123456788',
            'role' => 'admin',
            'user_id' => $user->id,
        ]);

        $response = $this->post('/login', [
            'email' => 'petugas-login@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('petugas.home', absolute: false));
        $this->assertAuthenticatedAs($user);
    }
}