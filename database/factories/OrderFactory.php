<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']);
        
        return [
            'customer_id' => Customer::factory(),
            'status' => $status,
            'tracking_number' => in_array($status, ['shipped', 'delivered']) ? fake()->numerify('TRACK-########') : null,
            'carrier' => in_array($status, ['shipped', 'delivered']) ? fake()->randomElement(['FedEx', 'UPS', 'DHL', 'PostNL', 'DPD']) : null,
        ];
    }
}
