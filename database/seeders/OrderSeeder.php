<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 orders with customers and products
        Order::factory(20)->create()->each(function ($order) {
            // Attach 2-5 random products to each order
            $products = Product::inRandomOrder()->limit(rand(2, 5))->pluck('id');
            
            foreach ($products as $productId) {
                $order->products()->attach($productId, [
                    'quantity' => rand(1, 5)
                ]);
            }

            // Add timeline entry for the order with tracking only if shipped/delivered
            $order->addTimeline(
                $order->status,
                'Order ' . strtolower($order->status),
                in_array($order->status, ['shipped', 'delivered']) ? $order->tracking_number : null,
                in_array($order->status, ['shipped', 'delivered']) ? $order->carrier : null
            );
        });
    }
}
