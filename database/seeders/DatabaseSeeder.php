<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'fio' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'user',
            'phone' => '+71234567890',
            'password' => Hash::make('password'),
            'avatar' => '/images/default.png'
        ]);

        $this->call([CategorySeeder::class]);
        $this->call([AdminSeeder::class]);
    }
}
