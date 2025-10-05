<?php

namespace App\Events;

use App\Models\DonorHistory;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DonorHistoryStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public DonorHistory $donorHistory,
        public string $oldStatus,
        public string $newStatus
    ) {}
}
