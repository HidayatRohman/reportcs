<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Seeder untuk user dengan role Menejer, Spv, dan Cs
        User::factory()->create([
            'name' => 'Menejer',
            'email' => 'menejer@example.com',
            'role' => 'Menejer',
            'password' => bcrypt('password'),
        ]);
        User::factory()->create([
            'name' => 'Spv',
            'email' => 'spv@example.com',
            'role' => 'Spv',
            'password' => bcrypt('password'),
        ]);
        User::factory()->create([
            'name' => 'Cs',
            'email' => 'cs@example.com',
            'role' => 'Cs',
            'password' => bcrypt('password'),
        ]);
    }
}
