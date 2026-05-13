<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResendVerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UpdateProfileController;
use App\Http\Controllers\Auth\VerifyAccountController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\NotificationApiController;
use Illuminate\Support\Facades\Route;


Route::view("/", "welcome")->name('home');
Route::view('/login', 'auth.login')->name('login');
Route::view("/register","auth.register")->name("register");

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'fr', 'ar'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::post("/register",RegisterController::class);
Route::post('/login', LoginController::class);

Route::get('/forgot-password', function() {
    try {
        return view('auth.forgot-password');
    } catch (\Exception $e) {
        return "Rendering Error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine();
    }
})->name("password.request");

Route::post("/forgot-password", ForgotPasswordController::class)->name("password.email");

Route::view("/reset-password/{token}","auth.reset-password")->name("password.reset");
Route::post("/reset-password", ResetPasswordController::class)->name("password.update");

Route::view("/verify-account/{identifier}","auth.verify-account")->name("account.verify");
Route::post("/verify-account",VerifyAccountController::class);
Route::post("/send-verification-otp", ResendVerificationController::class)->name("account.resend");

Route::middleware("auth")->group(function(){
    Route::middleware('role:admin,staff')->group(function () {
        Route::get('/orders/export', [OrderController::class, 'export'])->name('orders.export');
        Route::get('/customers/export', [CustomerController::class, 'export'])->name('customers.export');
        Route::resource("customers", CustomerController::class)->except(['show']);
        Route::delete('/products/bulk-destroy', [ProductController::class, 'bulkDestroy'])->name('products.bulkDestroy');
        Route::resource("products", ProductController::class)->except(['index', 'show']);
    });

    Route::get("/dashboard", [DashboardController::class, "index"])->name("dashboard.index");
    Route::resource("customers", CustomerController::class)->only(['show']);
    Route::resource("products", ProductController::class)->only(['index', 'show']);
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::resource("orders", OrderController::class);

    Route::view('/profile', 'auth.profile')->name("profile");
    Route::put('/profile', UpdateProfileController::class);
    Route::post("/change-password", ChangePasswordController::class);

    Route::post('/profile/notifications', \App\Http\Controllers\Auth\UpdateNotificationSettingsController::class)->name('profile.notifications.update');
    Route::post('/notifications/mark-all-read', [NotificationApiController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{id}/mark-read', [NotificationApiController::class, 'markRead'])->name('notifications.markRead');
    Route::get('/api/notifications', [NotificationApiController::class, 'index'])->name('api.notifications.index');
    
    // Dashboard API Metrics
    Route::get('/api/dashboard/metrics', [DashboardApiController::class, 'metrics'])->name('api.dashboard.metrics');

    Route::post('/logout', LogoutController::class)->name("logout");
});

// ══════════════════════════════════════════
// EMERGENCY HOSTING FIX ROUTE
// ══════════════════════════════════════════
Route::get('/fix-system', function() {
    try {
        $output = "--- Starting Deep System Fix ---<br>";
        
        // 1. Clear all caches
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        $output .= "✅ Caches cleared.<br>";

        // 2. Fix Storage Symlink
        $link = public_path('storage');
        if (file_exists($link) && is_link($link)) {
            unlink($link);
        }
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        $output .= "✅ Storage symlink recreated.<br>";

        // 3. File System Check
        $authPath = resource_path('views/auth');
        if (is_dir($authPath)) {
            $files = scandir($authPath);
            $output .= "✅ Folder 'resources/views/auth' found. Files: " . implode(', ', $files) . "<br>";
        } else {
            $output .= "❌ Folder 'resources/views/auth' NOT FOUND!<br>";
        }

        // 4. Database Table Verification
        $tables = ['users', 'products', 'orders', 'notifications', 'password_reset_tokens'];
        foreach ($tables as $table) {
            if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
                $output .= "✅ Table '{$table}' exists.<br>";
            } else {
                $output .= "❌ Table '{$table}' IS MISSING!<br>";
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            }
        }

        return $output . "<br>🚀 **Diagnostic Complete!**";
    } catch (\Exception $e) {
        return "❌ Critical Error: " . $e->getMessage();
    }
});