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
use Illuminate\Support\Facades\File;


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

Route::view("/forgot-password","auth.forgot-password")->name("password.request");
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
// STORAGE PROXY (Fixes broken photos on Cloud Hosting)
// ══════════════════════════════════════════
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    
    if (!File::exists($fullPath)) {
        abort(404);
    }

    $file = File::get($fullPath);
    $type = File::mimeType($fullPath);

    return response($file)->header("Content-Type", $type);
})->where('path', '.*');

// ══════════════════════════════════════════
// SYSTEM RECOVERY (Run once to clear caches)
// ══════════════════════════════════════════
Route::get('/fix-system', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return "✅ System caches cleared! Your photos should now work.";
    } catch (\Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});