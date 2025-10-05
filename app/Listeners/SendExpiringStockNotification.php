<?php

namespace App\Listeners;

use App\Events\BloodStockExpired;
use App\Notifications\ExpiringStockNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class SendExpiringStockNotification implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var array
     */
    public $backoff = [60, 180, 300];

    /**
     * Handle the event.
     *
     * @param  BloodStockExpired  $event
     * @return void
     */
    public function handle(BloodStockExpired $event)
    {
        $admins = \App\Models\User::role('admin')->get();
        
        Notification::send($admins, new ExpiringStockNotification($event->bloodStock));
    }

    /**
     * Handle a job failure.
     *
     * @param  \App\Events\BloodStockExpired  $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(BloodStockExpired $event, $exception)
    {
        Log::error('Gagal mengirim notifikasi stok kadaluarsa: ' . $exception->getMessage(), [
            'blood_stock_id' => $event->bloodStock->id,
            'error' => $exception
        ]);
    }
}
