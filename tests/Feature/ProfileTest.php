<?php

namespace Tests\Feature;

use App\Models\Petugas;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();
        Warga::create([
            'nama' => 'Lama User',
            'nomor_hp' => '0800000000',
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'nama' => 'Test User',
                'nomor_hp' => '08123456789',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();
        $warga = $user->warga()->first();

        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
        $this->assertSame('Test User', $warga->nama);
        $this->assertSame('08123456789', $warga->nomor_hp);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();
        Warga::create([
            'nama' => 'Test User',
            'nomor_hp' => '08123456789',
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'nama' => 'Test User',
                'nomor_hp' => '08123456789',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_petugas_profile_page_displays_petugas_fields(): void
    {
        $user = User::factory()->create([
            'email' => 'petugas@example.com',
            'role' => 'petugas',
        ]);

        Petugas::create([
            'nip' => '198812122026011001',
            'nama' => 'Petugas Contoh',
            'nomor_hp' => '081234567890',
            'role' => 'admin',
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
        $response->assertSee('Profil Petugas');
        $response->assertSee('petugas@example.com');
        $response->assertSee('198812122026011001');
        $response->assertSee('Petugas Contoh');
        $response->assertSee('081234567890');
    }
}
