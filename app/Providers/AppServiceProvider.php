<?php

namespace App\Providers;

use App\Services\Analytics\AnalyticsService;
use App\Services\Analytics\MenuAnalyticsService;
use App\Services\Analytics\OrderAnalyticsService;
use App\Services\Analytics\SalesAnalyticsService;
use App\Services\Analytics\StaffPerformanceService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register analytics services
        $this->app->singleton(SalesAnalyticsService::class, function ($app) {
            return new SalesAnalyticsService();
        });

        $this->app->singleton(MenuAnalyticsService::class, function ($app) {
            return new MenuAnalyticsService();
        });

        $this->app->singleton(OrderAnalyticsService::class, function ($app) {
            return new OrderAnalyticsService();
        });

        $this->app->singleton(StaffPerformanceService::class, function ($app) {
            return new StaffPerformanceService();
        });

        $this->app->singleton(AnalyticsService::class, function ($app) {
            return new AnalyticsService(
                $app->make(SalesAnalyticsService::class),
                $app->make(MenuAnalyticsService::class),
                $app->make(OrderAnalyticsService::class),
                $app->make(StaffPerformanceService::class)
            );
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

