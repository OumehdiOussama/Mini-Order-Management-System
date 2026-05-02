<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\OrderSeeder;
use Database\Seeders\OrderTimelineSeeder;
use Database\Seeders\ProductSeeder;
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
        User::updateOrCreate(
            ['email' => env("ADMIN_EMAIL")],
            [
                'name' => "OUSSAMA OUMEHDI",
                'password' => Hash::make(env("ADMIN_PASSWOPRD")),
                'role' => "admin",
                'account_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'account_verified_at' => now(),
            ]
        );

        // Seed demo data
        $this->call([
            CustomerSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
            OrderTimelineSeeder::class,
        ]);
    }
}
