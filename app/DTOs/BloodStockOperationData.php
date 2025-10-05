<?php

namespace App\DTOs;

class BloodStockOperationData
{
    public function __construct(
        public string $bloodType,
        public string $rhesus,
        public int $amount,
        public ?string $referenceType = null,
        public ?int $referenceId = null,
        public ?string $notes = null
    ) {}

    public function getCompositeKey(): string
    {
        return "{$this->bloodType}_{$this->rhesus}";
    }
}
