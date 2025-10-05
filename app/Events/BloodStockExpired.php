<?php

namespace App\Events;

use App\Models\BloodStock;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BloodStockExpired
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bloodStock;

    public function __construct(BloodStock $bloodStock)
    {
        $this->bloodStock = $bloodStock;
    }
}
