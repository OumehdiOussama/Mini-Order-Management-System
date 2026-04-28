<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(\App\Models\Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if ($notifiable->role === 'customer') {
            return ['mail', 'database'];
        }
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $status = ucfirst($this->order->status);
        $message = (new MailMessage)
            ->subject("Order Update: #{$this->order->id} is now {$status}")
            ->line("Your order #{$this->order->id} status has been updated to: {$status}.")
            ->line("Total Price: {$this->order->getTotalPrice()} MAD");

        if ($this->order->status === 'shipped') {
            $message->line("Tracking Number: {$this->order->tracking_number}")
                    ->line("Carrier: {$this->order->carrier}");
        }

        return $message->action('View Order', url('/orders/' . $this->order->id))
                       ->line('Thank you for shopping with us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Order Status Updated',
            'message' => 'Order #' . $this->order->id . ' is now ' . $this->order->status,
            'type' => 'info',
            'order_id' => $this->order->id,
            'status' => $this->order->status,
        ];
    }
}
