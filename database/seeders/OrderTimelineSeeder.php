<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderTimeline;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderTimelineSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create logical timeline progressions for orders
        // Timeline should follow: pending → processing → shipped → delivered (or cancelled anytime)
        
        $orders = Order::all();
        $statusProgression = ['pending', 'processing', 'shipped', 'delivered'];

        foreach ($orders as $order) {
            // Find where the order is in the progression
            $currentStatusIndex = array_search($order->status, $statusProgression);
            
            if ($currentStatusIndex === false) {
                // Order is cancelled, skip adding progression entries
                continue;
            }

            // Add timeline entries for all statuses BEFORE the current one
            $hoursAgo = 72; // Start 72 hours ago
            for ($i = 0; $i < $currentStatusIndex; $i++) {
                $status = $statusProgression[$i];
                $hasTracking = in_array($status, ['shipped', 'delivered']);

                OrderTimeline::create([
                    'order_id' => $order->id,
                    'status' => $status,
                    'notes' => 'Order ' . $status,
                    'tracking_number' => $hasTracking ? $order->tracking_number : null,
                    'carrier' => $hasTracking ? $order->carrier : null,
                    'created_at' => now()->subHours($hoursAgo),
                ]);
                
                $hoursAgo -= 24; // Each status is 24 hours apart
            }
        }
    }
}
