<?php

namespace App\Events;

use App\Models\BloodRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BloodRequestStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bloodRequest;
    public $oldStatus;

    public function __construct(BloodRequest $bloodRequest, string $oldStatus = null)
    {
        $this->bloodRequest = $bloodRequest;
        $this->oldStatus = $oldStatus;
    }
}
