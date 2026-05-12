<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewOrderCreated;
use App\Notifications\OrderStatusUpdated;

class OrderService
{
    /**
     * Create a new order with its products and timeline.
     */
    public function createOrder(array $data, User $user): Order
    {
        return DB::transaction(function () use ($data, $user) {
            $order = Order::create([
                'customer_id' => $data['customer_id'],
                'status' => 'pending',
            ]);

            foreach ($data['products'] as $productId) {
                $quantity = (int) $data['quantities'][$productId];
                $order->products()->attach($productId, ['quantity' => $quantity]);
            }

            $order->addTimeline('pending', 'Order created');

            $this->notifyOrderCreated($order);

            return $order;
        });
    }

    /**
     * Update an order's status and tracking information.
     */
    public function updateOrderStatus(Order $order, array $data): Order
    {
        $statusChanged = $order->status !== $data['status'];

        $trackingNumber = $data['status'] === 'shipped' ? ($data['tracking_number'] ?? null) : $order->tracking_number;
        $carrier = $data['status'] === 'shipped' ? ($data['carrier'] ?? null) : $order->carrier;

        $order->update([
            'status' => $data['status'],
            'tracking_number' => $trackingNumber,
            'carrier' => $carrier,
        ]);

        if ($statusChanged) {
            $order->addTimeline(
                $data['status'],
                $data['notes'] ?? null,
                $trackingNumber,
                $carrier
            );
            $this->notifyOrderStatusUpdated($order);
        }

        return $order;
    }

    /**
     * Notify admins and customer about new order.
     */
    protected function notifyOrderCreated(Order $order): void
    {
        $adminsAndStaff = User::whereIn('role', ['admin', 'staff'])->get();
        Notification::send($adminsAndStaff, new NewOrderCreated($order));

        if ($order->customer && $order->customer->user) {
            $order->customer->user->notify(new OrderStatusUpdated($order));
        }
    }

    /**
     * Notify customer about order status update.
     */
    protected function notifyOrderStatusUpdated(Order $order): void
    {
        if ($order->customer && $order->customer->user) {
            $order->customer->user->notify(new OrderStatusUpdated($order));
        }
    }
}
