<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = [
            'Laptop Computer',
            'Wireless Mouse',
            'USB-C Cable',
            'Monitor 27"',
            'Keyboard Mechanical',
            'Webcam HD',
            'Headphones Pro',
            'USB Hub 4 Ports',
            'Phone Stand',
            'Screen Protector',
            'Laptop Bag',
            'Desk Lamp LED',
            'Mouse Pad Large',
            'Cable Organizer',
            'Portable Charger',
        ];

        return [
            'name' => fake()->randomElement($products),
            'price' => fake()->randomFloat(2, 10, 500),
        ];
    }
}
