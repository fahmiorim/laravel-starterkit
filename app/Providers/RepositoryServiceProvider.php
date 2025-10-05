<?php

namespace App\Providers;

use App\Repositories\Contracts\DonorCardRepositoryInterface;
use App\Repositories\Eloquent\DonorCardRepository;
use App\Repositories\Contracts\BloodStockRepositoryInterface;
use App\Repositories\Eloquent\BloodStockRepository;
use App\Repositories\Contracts\BloodRequestRepositoryInterface;
use App\Repositories\Eloquent\BloodRequestRepository;
use App\Repositories\Contracts\DonationScheduleRepositoryInterface;
use App\Repositories\Eloquent\DonationScheduleRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            DonorCardRepositoryInterface::class,
            DonorCardRepository::class
        );
        
        $this->app->bind(
            BloodStockRepositoryInterface::class,
            BloodStockRepository::class
        );
        
        $this->app->bind(
            DonationScheduleRepositoryInterface::class,
            DonationScheduleRepository::class
        );
        
        $this->app->bind(
            BloodRequestRepositoryInterface::class,
            BloodRequestRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
