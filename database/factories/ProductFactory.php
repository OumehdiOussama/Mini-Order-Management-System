<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $faker;

    public function __construct()
    {
        parent::__construct();

        $this->faker = \Faker\Factory::create('fr_FR');
    }

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
            'name' => $this->faker->randomElement($products),

            'price' => $this->faker->randomFloat(2, 10, 500),
        ];
    }
}