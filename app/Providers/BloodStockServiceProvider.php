<?php

namespace App\Providers;

use App\Repositories\Contracts\BloodStockRepositoryInterface;
use App\Repositories\Eloquent\BloodStockRepository;
use App\Services\BloodStockOperationService;
use App\Services\BloodStockService;
use Illuminate\Support\ServiceProvider;

class BloodStockServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind repository interface to implementation
        $this->app->bind(
            BloodStockRepositoryInterface::class,
            BloodStockRepository::class
        );

        // Register services
        $this->app->singleton(BloodStockOperationService::class, function ($app) {
            return new BloodStockOperationService(
                $app->make(BloodStockRepositoryInterface::class)
            );
        });

        $this->app->singleton(BloodStockService::class, function ($app) {
            return new BloodStockService(
                $app->make(BloodStockRepositoryInterface::class),
                $app->make(BloodStockOperationService::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
