<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        for ($i = 0; $i < 17; $i++) {
            $user = \App\Models\User::create([
                'email' => $faker->unique()->safeEmail(),
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'warga',
            ]);

            \App\Models\Warga::create([
                'nama_warga' => $faker->name(),
                'nomor_hp' => $faker->phoneNumber(),
                'user_id' => $user->id,
            ]);
        }
    }
}
