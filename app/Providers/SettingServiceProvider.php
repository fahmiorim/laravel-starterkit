<?php

namespace App\Providers;

use App\Services\SettingService;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('setting', function ($app) {
            return new SettingService();
        });
        
        // Register the facade
        $this->app->alias('setting', SettingService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__.'/../../config/settings.php' => config_path('settings.php'),
        ], 'config');
    }
}
