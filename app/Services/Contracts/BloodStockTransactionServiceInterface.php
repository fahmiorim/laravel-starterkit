<?php

namespace App\Services\Contracts;

use App\Models\BloodStockTransaction;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BloodStockTransactionServiceInterface
{
    /**
     * Get paginated transactions with optional filters.
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Retrieve selections needed for create/edit forms.
     */
    public function getFormData(): array;

    /**
     * Create a new blood stock transaction.
     */
    public function create(array $data, User $creator): BloodStockTransaction;

    /**
     * Update an existing transaction.
     */
    public function update(BloodStockTransaction $transaction, array $data): BloodStockTransaction;

    /**
     * Remove a transaction.
     */
    public function delete(BloodStockTransaction $transaction): bool;

    /**
     * Approve a transaction and sync stock.
     */
    public function approve(BloodStockTransaction $transaction, User $approver): BloodStockTransaction;

    /**
     * Reject a transaction with a reason.
     */
    public function reject(BloodStockTransaction $transaction, User $approver, string $reason): BloodStockTransaction;
}
