<?php

namespace App\Providers;

use App\Repositories\Contracts\DonorRepositoryInterface;
use App\Repositories\Eloquent\DonorRepository;
use App\Services\BloodRequestService;
use App\Services\BloodStockTransactionService;
use App\Services\Contracts\BloodRequestServiceInterface;
use App\Services\Contracts\BloodStockTransactionServiceInterface;
use App\Services\Contracts\DashboardServiceInterface;
use App\Services\Contracts\DonorServiceInterface;
use App\Services\DashboardService;
use App\Services\DonorService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DashboardServiceInterface::class, DashboardService::class);
        $this->app->bind(DonorServiceInterface::class, DonorService::class);
        $this->app->bind(DonorRepositoryInterface::class, DonorRepository::class);
        $this->app->bind(BloodRequestServiceInterface::class, BloodRequestService::class);
        $this->app->bind(BloodStockTransactionServiceInterface::class, BloodStockTransactionService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
