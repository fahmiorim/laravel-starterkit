<?php

namespace App\Events;

use App\Models\DonorHistory;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DonorHistoryCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public DonorHistory $donorHistory
    ) {}
}
