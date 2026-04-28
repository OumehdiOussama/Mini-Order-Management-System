<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\App\Models\User::firstOrCreate(['email' => 'staff@example.com'], ['name' => 'Staff', 'phone' => '111', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'role' => 'staff', 'otp' => '111111']); 
\App\Models\User::firstOrCreate(['email' => 'customer@example.com'], ['name' => 'Customer', 'phone' => '222', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'role' => 'customer', 'otp' => '222222']); 

$admin = \App\Models\User::where('role', 'admin')->first(); 
if(!$admin) { 
    \App\Models\User::firstOrCreate(['email' => 'admin@example.com'], ['name' => 'Admin', 'phone' => '333', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'role' => 'admin', 'otp' => '333333']); 
}
echo "Users created successfully.\n";
