<?php

namespace App\Events;

use App\Models\Donor;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DonorEligibilityChecked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $donor;
    public $isEligible;
    public $reason;

    public function __construct(Donor $donor, bool $isEligible, string $reason = '')
    {
        $this->donor = $donor;
        $this->isEligible = $isEligible;
        $this->reason = $reason;
    }
}
