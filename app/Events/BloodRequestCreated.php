<?php

namespace App\Events;

use App\Models\BloodRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BloodRequestCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bloodRequest;

    public function __construct(BloodRequest $bloodRequest)
    {
        $this->bloodRequest = $bloodRequest;
    }
}
