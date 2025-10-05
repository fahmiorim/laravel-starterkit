<?php

namespace App\Listeners;

use App\Events\BloodStockLow;
use App\Notifications\LowStockNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class SendLowStockNotification implements ShouldQueue
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
     * @param  BloodStockLow  $event
     * @return void
     */
    public function handle(BloodStockLow $event)
    {
        $admins = \App\Models\User::role('admin')->get();
        
        Notification::send($admins, new LowStockNotification($event->bloodStock));
    }

    /**
     * Handle a job failure.
     *
     * @param  \App\Events\BloodStockLow  $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(BloodStockLow $event, $exception)
    {
        Log::error('Gagal mengirim notifikasi stok rendah: ' . $exception->getMessage(), [
            'blood_stock_id' => $event->bloodStock->id,
            'error' => $exception
        ]);
    }
}
