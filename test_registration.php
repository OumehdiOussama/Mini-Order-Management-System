<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 1. Make the first user an admin to receive notifications
$admin = \App\Models\User::first();
if($admin) {
    $admin->role = 'admin';
    $admin->save();
}

// 2. Simulate registration
$email = 'test_reg_' . time() . '@example.com';
$user = \App\Models\User::create([
    "name" => "Automated Test",
    "phone" => "06" . rand(10000000, 99999999),
    "email" => $email,
    "password" => \Illuminate\Support\Facades\Hash::make("password"),
    "otp" => 123456,
    "role" => "customer"
]);

// Dispatch event (as done in RegisterController)
event(new \App\Events\UserRegistered($user));

// 3. Verify Customer profile was created
$customer = \App\Models\Customer::where('user_id', $user->id)->first();
if ($customer) {
    echo "SUCCESS: Customer profile created for {$email}\n";
} else {
    echo "ERROR: Customer profile NOT created.\n";
}

// 4. Verify Notification was sent
$notifications = \Illuminate\Support\Facades\DB::table('notifications')
    ->where('notifiable_id', $admin->id ?? 0)
    ->orderBy('created_at', 'desc')
    ->first();

if ($notifications) {
    $data = json_decode($notifications->data, true);
    if (isset($data['customer_id']) && $data['customer_id'] == $customer->id) {
        echo "SUCCESS: Notification sent to admin.\n";
    } else {
        echo "ERROR: Notification data mismatch.\n";
    }
} else {
    echo "ERROR: Notification NOT sent.\n";
}
