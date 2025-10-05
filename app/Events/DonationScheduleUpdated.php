<?php

namespace App\Events;

use App\Models\DonationSchedule;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DonationScheduleUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $schedule;

    public function __construct(DonationSchedule $schedule)
    {
        $this->schedule = $schedule;
    }
}
