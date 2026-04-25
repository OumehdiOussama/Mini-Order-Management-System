<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UpdateProfileController;
use App\Http\Controllers\Auth\VerifyAccountController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::view("/","auth.login");
Route::view('/login', 'auth.login')->name('login');
Route::view("/register","auth.register")->name("register");

Route::post("/register",RegisterController::class);
Route::post('/login', LoginController::class);

Route::view("/forgot-password","auth.forgot-password")->name("password.request");
Route::post("/forgot-password", ForgotPasswordController::class)->name("password.email");

Route::view("/reset-password/{token}","auth.reset-password")->name("password.reset");
Route::post("/reset-password", ResetPasswordController::class)->name("password.update");

Route::view("/verify-account/{identifier}","auth.verify-account")->name("account.verify");
Route::post("/verify-account",VerifyAccountController::class);

Route::middleware("auth")->group(function(){
    Route::get("/dashboard", [DashboardController::class, "index"])->name("dashboard.index");
    Route::resource("customers", CustomerController::class);
    Route::resource("products", ProductController::class);
    Route::resource("orders", OrderController::class);

    Route::view('/profile', 'auth.profile')->name("profile");
    Route::put('/profile', UpdateProfileController::class);
    Route::post("/change-password", ChangePasswordController::class);

    Route::post('/logout', LogoutController::class)->name("logout");
});