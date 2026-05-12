<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderCreated extends Notification implements ShouldQueue
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
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Order Confirmation: #{$this->order->id}")
            ->view('emails.order-confirmation', ['order' => $this->order]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Order Created',
            'message' => 'A new order <strong>#' . $this->order->id . '</strong> has been placed by <strong>' . ($this->order->customer->name ?? 'Guest') . '</strong>.',
            'type' => 'success',
            'order_id' => $this->order->id,
            'actor_name' => $this->order->customer->name ?? 'Guest',
            'actor_photo' => $this->order->customer->user->photo ?? null,
            'amount' => $this->order->getTotalPrice() . ' MAD',
        ];
    }
}
