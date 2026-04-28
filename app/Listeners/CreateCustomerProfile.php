<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateCustomerProfile
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $user = $event->user;

        if ($user->role === 'customer') {
            $customer = \App\Models\Customer::create([
                'user_id' => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'phone'   => $user->phone,
            ]);

            $adminsAndStaff = \App\Models\User::whereIn('role', ['admin', 'staff'])->get();
            \Illuminate\Support\Facades\Notification::send($adminsAndStaff, new \App\Notifications\NewCustomerRegistered($customer));
        }
    }
}
