<?php

namespace Database\Seeders;

use App\Models\Petugas;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $user = User::firstOrCreate(
                ['email' => 'petugas@example.com'],
                [
                    'password' => bcrypt('password'),
                    'role' => 'petugas',
                ]
            );

            if ($user->role !== 'petugas') {
                $user->update(['role' => 'petugas']);
            }

            Petugas::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nip' => '198812122026011001',
                    'nama' => 'Petugas Contoh',
                    'nomor_hp' => '081234567890',
                    'role' => 'admin',
                ]
            );
        });
    }
}
