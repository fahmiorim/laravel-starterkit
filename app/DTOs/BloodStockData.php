<?php

namespace App\DTOs;

use Carbon\Carbon;

class BloodStockData
{
    public function __construct(
        public string $bloodType,
        public string $rhesus,
        public int $quantity,
        public ?Carbon $donationDate = null,
        public ?Carbon $expiryDate = null,
        public ?int $updatedBy = null,
        public ?int $lowStockThreshold = 5
    ) {
        $this->donationDate = $donationDate ?? now();
        $this->expiryDate = $expiryDate ?? now()->addDays(35);
    }

    public function toArray(): array
    {
        return [
            'blood_type' => $this->bloodType,
            'rhesus' => $this->rhesus,
            'quantity' => $this->quantity,
            'donation_date' => $this->donationDate,
            'expiry_date' => $this->expiryDate,
            'updated_by' => $this->updatedBy,
            'low_stock_threshold' => $this->lowStockThreshold,
        ];
    }
}
