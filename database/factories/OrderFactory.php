<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $faker;

    public function __construct()
    {
        parent::__construct();

        $this->faker = \Faker\Factory::create('fr_FR');
    }

    public function definition(): array
    {
        $status = $this->faker->randomElement([
            'pending',
            'processing',
            'shipped',
            'delivered',
            'cancelled'
        ]);

        return [
            'customer_id' => Customer::factory(),

            'status' => $status,

            'tracking_number' => in_array($status, ['shipped', 'delivered'])
                ? 'TRACK-' . $this->faker->numerify('########')
                : null,

            'carrier' => in_array($status, ['shipped', 'delivered'])
                ? $this->faker->randomElement(['FedEx', 'UPS', 'DHL', 'PostNL', 'DPD'])
                : null,
        ];
    }
}