<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // penting untuk force HTTPS

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
        // Jika environment bukan local, paksa semua URL pakai HTTPS
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
    }
}