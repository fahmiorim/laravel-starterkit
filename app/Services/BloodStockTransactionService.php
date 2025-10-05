<?php

namespace App\Services;

use App\Models\BloodStock;
use App\Models\BloodStockTransaction;
use App\Models\User;
use App\Services\Contracts\BloodStockTransactionServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BloodStockTransactionService implements BloodStockTransactionServiceInterface
{
    public function __construct(
        private readonly BloodStockService $bloodStockService
    ) {}

    /**
     * {@inheritdoc}
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return BloodStockTransaction::with(['bloodStock', 'creator', 'approver'])
            ->when(!empty($filters['status']), static function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormData(): array
    {
        return [
            'bloodStocks' => BloodStock::orderBy('blood_type')->orderBy('rhesus')->get(),
            'users' => User::orderBy('name')->get(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data, User $creator): BloodStockTransaction
    {
        return DB::transaction(function () use ($data, $creator) {
            $data['created_by'] = $creator->id;
            $data['status'] = 'pending';

            if ($creator->hasRole('admin')) {
                $data['approved_by'] = $creator->id;
                $data['approved_at'] = now();
                $data['status'] = 'approved';
            }

            $transaction = BloodStockTransaction::create($data);

            if ($transaction->isApproved()) {
                $this->syncStock($transaction);
            }

            return $transaction->load(['bloodStock', 'creator', 'approver']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function update(BloodStockTransaction $transaction, array $data): BloodStockTransaction
    {
        return DB::transaction(function () use ($transaction, $data) {
            $wasApproved = $transaction->isApproved();

            $transaction->update($data);
            $transaction->refresh();

            if (!$wasApproved && $transaction->isApproved()) {
                $this->syncStock($transaction);
            }

            return $transaction;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function delete(BloodStockTransaction $transaction): bool
    {
        return (bool) $transaction->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function approve(BloodStockTransaction $transaction, User $approver): BloodStockTransaction
    {
        return DB::transaction(function () use ($transaction, $approver) {
            if ($transaction->isApproved()) {
                return $transaction;
            }

            $transaction->update([
                'status' => 'approved',
                'approved_by' => $approver->id,
                'approved_at' => now(),
                'rejection_reason' => null,
            ]);

            $transaction->refresh();
            $this->syncStock($transaction);

            return $transaction;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function reject(BloodStockTransaction $transaction, User $approver, string $reason): BloodStockTransaction
    {
        return DB::transaction(function () use ($transaction, $approver, $reason) {
            $transaction->update([
                'status' => 'rejected',
                'approved_by' => $approver->id,
                'approved_at' => now(),
                'rejection_reason' => $reason,
            ]);

            return $transaction->refresh();
        });
    }

    /**
     * Update the stock quantity according to transaction type.
     */
    protected function syncStock(BloodStockTransaction $transaction): void
    {
        $stock = BloodStock::query()
            ->whereKey($transaction->blood_stock_id)
            ->lockForUpdate()
            ->first();

        if (!$stock) {
            return;
        }

        if ($transaction->type === 'in') {
            $stock->increment('quantity', $transaction->quantity);
        } else {
            $stock->decrement('quantity', $transaction->quantity);
        }

        $this->bloodStockService->checkStockLevels($stock->fresh());
    }
}


