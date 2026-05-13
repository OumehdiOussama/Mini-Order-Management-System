<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewOrderCreated;
use App\Notifications\OrderStatusUpdated;
use App\Notifications\CustomerOrderCreated;
use App\Models\Product;
use Exception;

class OrderService
{
    /**
     * Create a new order with its products and timeline.
     *
     * $data['products']   = associative array [ productId => productId ] (from checkboxes)
     * $data['quantities'] = associative array [ productId => qty ]
     */
    public function createOrder(array $data, User $user): Order
    {
        return DB::transaction(function () use ($data, $user) {
            $order = Order::create([
                'customer_id' => $data['customer_id'],
                'status'      => 'pending',
            ]);

            $productsToAttach = [];
            $totalAmount = 0;
            foreach ($data['products'] as $productId => $val) {
                $quantity = (int) ($data['quantities'][$productId] ?? 1);
                if ($quantity >= 1) {
                    $product = Product::findOrFail($productId);
                    
                    // Validate stock
                    if ($product->stock < $quantity) {
                        throw new Exception("Insufficient stock for product: {$product->name}");
                    }

                    // Atomic decrement
                    $product->decrement('stock', $quantity);
                    
                    $productsToAttach[$productId] = ['quantity' => $quantity];
                    $totalAmount += $product->price * $quantity;
                }
            }
            $order->products()->attach($productsToAttach);
            $order->update(['total_amount' => $totalAmount]);

            $order->addTimeline('pending', 'Order created');

            $this->notifyOrderCreated($order, $user);

            // Invalidate dashboard metrics cache
            Cache::forget('dashboard_metrics_api');

            return $order->load(['customer', 'products']);
        });
    }

    /**
     * Update an order's status and tracking information.
     */
    public function updateOrderStatus(Order $order, array $data): Order
    {
        $statusChanged = $order->status !== $data['status'];

        $trackingNumber = $data['status'] === 'shipped'
            ? ($data['tracking_number'] ?? null)
            : $order->tracking_number;

        $carrier = $data['status'] === 'shipped'
            ? ($data['carrier'] ?? null)
            : $order->carrier;

        $order->update([
            'status'          => $data['status'],
            'tracking_number' => $trackingNumber,
            'carrier'         => $carrier,
        ]);

        if ($statusChanged) {
            // Restore stock if cancelled
            if ($data['status'] === 'cancelled') {
                foreach ($order->products as $product) {
                    $product->increment('stock', $product->pivot->quantity);
                }
            }

            $order->addTimeline(
                $data['status'],
                $data['notes'] ?? null,
                $trackingNumber,
                $carrier
            );
            $this->notifyOrderStatusUpdated($order);
            
            // Invalidate dashboard metrics cache
            Cache::forget('dashboard_metrics_api');
        }

        return $order;
    }

    /**
     * Notify admins and customer about new order.
     */
    protected function notifyOrderCreated(Order $order, User $creator): void
    {
        try {
            $adminsAndStaff = User::whereIn('role', ['admin', 'staff'])->get();
            Notification::send($adminsAndStaff, new NewOrderCreated($order));

            if ($order->customer && $order->customer->user) {
                $order->customer->user->notify(new CustomerOrderCreated($order));
            }
        } catch (\Exception $e) {
            // Notification failure should NOT break order creation
            \Log::warning('Order creation notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Notify customer about order status update.
     */
    protected function notifyOrderStatusUpdated(Order $order): void
    {
        try {
            if ($order->customer && $order->customer->user) {
                $order->customer->user->notify(new OrderStatusUpdated($order));
            }
        } catch (\Exception $e) {
            \Log::warning('Order status notification failed: ' . $e->getMessage());
        }
    }
}
