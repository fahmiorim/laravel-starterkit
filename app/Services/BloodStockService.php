<?php

namespace App\Services;

use App\DTOs\BloodStockData;
use App\DTOs\BloodStockOperationData;
use App\Events\BloodStockLow;
use App\Events\BloodStockExpired;
use App\Models\BloodStock;
use App\Repositories\Contracts\BloodStockRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BloodStockService
{
    public function __construct(
        private BloodStockRepositoryInterface $bloodStockRepository,
        private BloodStockOperationService $operationService
    ) {}

    /**
     * Get all blood stocks
     */
    public function getAllStocks(): Collection
    {
        return $this->bloodStockRepository->all();
    }

    /**
     * Get paginated blood stocks
     */
    public function getPaginatedStocks(int $perPage = 15): LengthAwarePaginator
    {
        return $this->bloodStockRepository->paginate($perPage);
    }

    /**
     * Get blood stock by ID
     */
    public function getStockById(int $id): ?BloodStock
    {
        return $this->bloodStockRepository->find($id);
    }

    /**
     * Create new blood stock
     */
    public function createStock(BloodStockData $stockData): BloodStock
    {
        return DB::transaction(function () use ($stockData) {
            $stock = $this->bloodStockRepository->create($stockData->toArray());
            $this->checkStockLevels($stock);
            return $stock;
        });
    }

    /**
     * Update blood stock
     */
    public function updateStock(BloodStock $stock, BloodStockData $stockData): bool
    {
        return DB::transaction(function () use ($stock, $stockData) {
            $updated = $this->bloodStockRepository->update($stock, $stockData->toArray());
            if ($updated) {
                $this->checkStockLevels($stock->fresh());
            }
            return $updated;
        });
    }

    /**
     * Delete blood stock
     */
    public function deleteStock(BloodStock $stock): bool
    {
        return $this->bloodStockRepository->delete($stock);
    }

    /**
     * Add blood to stock using DTO
     */
    public function addToStock(BloodStockOperationData $operationData): bool
    {
        return $this->operationService->addToStock($operationData);
    }

    /**
     * Remove blood from stock using DTO
     */
    public function removeFromStock(BloodStockOperationData $operationData): bool
    {
        return $this->operationService->removeFromStock($operationData);
    }

    /**
     * Check stock levels and trigger events if needed
     */
    public function checkStockLevels(?BloodStock $specificStock = null): void
    {
        if ($specificStock) {
            $this->operationService->checkStockLevels($specificStock);
        } else {
            $this->checkAllStockLevels();
        }
    }

    /**
     * Check all stock levels
     */
    protected function checkAllStockLevels(): void
    {
        $lowStocks = $this->bloodStockRepository->getLowStock();
        $expiringSoon = $this->bloodStockRepository->getExpiringSoon();

        $lowStocks->each(fn($stock) => event(new BloodStockLow($stock)));
        $expiringSoon->each(fn($stock) => event(new BloodStockExpired($stock)));
    }

    /**
     * Get expiring soon stocks
     */
    public function getExpiringStocks(int $days = 7): Collection
    {
        return $this->bloodStockRepository->getExpiringSoon($days);
    }

    /**
     * Get low stock items
     */
    public function getLowStocks(int $threshold = 5): Collection
    {
        return $this->bloodStockRepository->getLowStock($threshold);
    }
}
