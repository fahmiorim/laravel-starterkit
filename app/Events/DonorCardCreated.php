<?php

namespace App\Events;

use App\Models\DonorCard;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DonorCardCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $donorCard;

    /**
     * Create a new event instance.
     *
     * @param DonorCard $donorCard
     * @return void
     */
    public function __construct(DonorCard $donorCard)
    {
        $this->donorCard = $donorCard;
    }
}
