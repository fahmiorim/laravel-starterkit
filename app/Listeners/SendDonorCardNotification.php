<?php

namespace App\Listeners;

use App\Events\DonorCardCreated;
use App\Notifications\DonorCardIssuedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendDonorCardNotification implements ShouldQueue
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
     * @param  DonorCardCreated  $event
     * @return void
     */
    public function handle(DonorCardCreated $event)
    {
        $donor = $event->donorCard->donor;
        
        // Kirim notifikasi ke pendonor
        if ($donor->email) {
            Notification::route('mail', $donor->email)
                ->notify(new DonorCardIssuedNotification($event->donorCard));
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  \App\Events\DonorCardCreated  $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(DonorCardCreated $event, $exception)
    {
        // Log error atau lakukan penanganan kegagalan lainnya
        Log::error('Gagal mengirim notifikasi kartu donor: ' . $exception->getMessage(), [
            'donor_card_id' => $event->donorCard->id,
            'error' => $exception
        ]);
    }
}
