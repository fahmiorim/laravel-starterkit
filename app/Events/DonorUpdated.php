<?php

namespace App\Events;

use App\Models\Donor;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DonorUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $donor;

    public function __construct(Donor $donor)
    {
        $this->donor = $donor;
    }
}
