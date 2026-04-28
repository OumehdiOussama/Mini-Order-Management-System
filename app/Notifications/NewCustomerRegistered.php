<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCustomerRegistered extends Notification
{
    use Queueable;

    public $customer;

    /**
     * Create a new notification instance.
     */
    public function __construct(\App\Models\Customer $customer)
    {
        $this->customer = $customer;
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Customer Registered',
            'message' => 'Customer ' . $this->customer->name . ' has just registered.',
            'type' => 'info',
            'customer_id' => $this->customer->id,
        ];
    }
}
