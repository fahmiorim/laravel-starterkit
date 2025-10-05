<?php

namespace App\Events;

use App\Models\DonationSchedule;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DonationScheduleStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $schedule;
    public $oldStatus;
    public $newStatus;

    public function __construct(DonationSchedule $schedule, string $oldStatus, string $newStatus)
    {
        $this->schedule = $schedule;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }
}
