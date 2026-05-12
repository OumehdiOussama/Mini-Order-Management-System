<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject("Order Update: #{$this->order->id} is now " . ucfirst($this->order->status))
            ->view('emails.order-status-updated', ['order' => $this->order]);
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
            'message' => 'Order <strong>#' . $this->order->id . '</strong> has been marked as <strong>' . $this->order->status . '</strong>.',
            'type' => 'info',
            'order_id' => $this->order->id,
            'status' => $this->order->status,
            'actor_name' => auth()->user()->name ?? 'System',
            'actor_photo' => auth()->user()->photo ?? null,
        ];
    }
}
