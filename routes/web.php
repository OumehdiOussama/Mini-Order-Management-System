<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UpdateProfileController;
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




Route::middleware("auth")->group(function(){
    Route::get("/dashboard", [DashboardController::class, "index"])->name("dashboard.index");
    Route::resource("customers", CustomerController::class);
    Route::resource("products", ProductController::class);
    Route::resource("orders", OrderController::class);



    Route::post('/logout', LogoutController::class)->name("logout");
});