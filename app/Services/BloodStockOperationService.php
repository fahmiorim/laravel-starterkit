<?php

namespace App\Services;

use App\DTOs\BloodStockOperationData;
use App\Events\BloodStockLow;
use App\Events\BloodStockExpired;
use App\Models\BloodStock;
use App\Repositories\Contracts\BloodStockRepositoryInterface;
use Illuminate\Support\Facades\DB;

class BloodStockOperationService
{
    public function __construct(
        private BloodStockRepositoryInterface $bloodStockRepository
    ) {}

    /**
     * Process stock addition
     */
    public function addToStock(BloodStockOperationData $operationData): bool
    {
        return DB::transaction(function () use ($operationData) {
            $stock = $this->bloodStockRepository->getByBloodType(
                $operationData->bloodType,
                $operationData->rhesus
            );

            if (!$stock) {
                $stock = $this->bloodStockRepository->create([
                    'blood_type' => $operationData->bloodType,
                    'rhesus' => $operationData->rhesus,
                    'quantity' => $operationData->amount,
                ]);
            } else {
                $this->bloodStockRepository->increaseStock(
                    $operationData->bloodType,
                    $operationData->rhesus,
                    $operationData->amount
                );
                $stock->refresh();
            }

            $this->checkStockLevels($stock);
            return true;
        });
    }

    /**
     * Process stock removal
     */
    public function removeFromStock(BloodStockOperationData $operationData): bool
    {
        return DB::transaction(function () use ($operationData) {
            $success = $this->bloodStockRepository->decreaseStock(
                $operationData->bloodType,
                $operationData->rhesus,
                $operationData->amount
            );
            
            if ($success) {
                $stock = $this->bloodStockRepository->getByBloodType(
                    $operationData->bloodType,
                    $operationData->rhesus
                );
                
                if ($stock) {
                    $this->checkStockLevels($stock);
                }
            }
            
            return $success;
        });
    }

    /**
     * Check stock levels and trigger events if needed
     * 
     * @param BloodStock $stock
     * @return void
     */
    public function checkStockLevels(BloodStock $stock): void
    {
        if (!$stock) {
            return;
        }

        // Check for low stock
        if ($stock->quantity <= ($stock->low_stock_threshold ?? 5)) {
            event(new BloodStockLow($stock));
        }

        // Check for expiring soon (within 7 days)
        if ($stock->expiry_date && $stock->expiry_date->diffInDays(now()) <= 7) {
            event(new BloodStockExpired($stock));
        }
    }
}
