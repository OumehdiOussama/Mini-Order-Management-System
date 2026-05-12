<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    protected $faker;

    public function __construct()
    {
        parent::__construct();

        $this->faker = \Faker\Factory::create('fr_FR');
    }

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),

            'email' => $this->faker->unique()->safeEmail(),

            'account_verified_at' => now(),

            'password' => static::$password ??= Hash::make('password'),

            'remember_token' => Str::random(10),

            'role' => 'customer',
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'account_verified_at' => null,
        ]);
    }
}