<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\TravelOrder;
use App\Observers\TravelOrderObserver;

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
        TravelOrder::observe(TravelOrderObserver::class);
    }
}
