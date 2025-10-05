<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        'App\Events\DonorCardCreated' => [
            'App\Listeners\SendDonorCardNotification',
        ],
        'App\Events\BloodStockLow' => [
            'App\Listeners\SendLowStockNotification',
        ],
        'App\Events\BloodStockExpired' => [
            'App\Listeners\SendExpiringStockNotification',
        ],
        \App\Events\DonationScheduleCreated::class => [
            \App\Listeners\NotifyUsersAboutNewSchedule::class,
        ],
        \App\Events\DonationScheduleUpdated::class => [
            // Add any listeners for update events if needed
        ],
        \App\Events\DonationScheduleStatusChanged::class => [
            \App\Listeners\NotifyUsersAboutNewSchedule::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        //
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }


    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
