<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL')],
            [
                'name' => 'OUSSAMA OUMEHDI',
                'password' => Hash::make(env('ADMIN_PASSWORD')),
                'role' => 'admin',
                'account_verified_at' => now(),
            ]
        );
    }
}