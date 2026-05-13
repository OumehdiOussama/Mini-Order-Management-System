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
        $this->call([
            AdminSeeder::class,
        ]);

        // demo data غير local
        if (app()->environment('local')) {
            $this->call([
                CustomerSeeder::class,
                ProductSeeder::class,
                OrderSeeder::class,
                OrderTimelineSeeder::class,
            ]);
        }
    }
}
