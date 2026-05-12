<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
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

            'phone' => $this->faker->randomElement(['06','07']) .
                       $this->faker->numerify('########'),
        ];
    }
}