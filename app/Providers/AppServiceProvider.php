<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Only force HTTPS in production AND if not running on localhost/127.0.0.1
        if ($this->app->environment('production') && !request()->is('localhost*', '127.0.0.1*')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
