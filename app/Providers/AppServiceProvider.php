<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GooglePlacesService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GooglePlacesService::class, function ($app) {
            return new GooglePlacesService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
