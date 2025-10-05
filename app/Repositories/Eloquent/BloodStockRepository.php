<?php

namespace App\Repositories\Eloquent;

use App\Models\BloodStock;
use App\Repositories\Contracts\BloodStockRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BloodStockRepository implements BloodStockRepositoryInterface
{
    protected $model;

    public function __construct(BloodStock $bloodStock)
    {
        $this->model = $bloodStock;
    }

    public function all(): Collection
    {
        return $this->model->orderBy('blood_type')->orderBy('rhesus')->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('updatedBy')
            ->orderBy('blood_type')
            ->orderBy('rhesus')
            ->paginate($perPage);
    }

    public function find(int $id): ?BloodStock
    {
        return $this->model->with('updatedBy')->find($id);
    }

    public function create(array $data): BloodStock
    {
        return $this->model->create($data);
    }

    public function update(BloodStock $bloodStock, array $data): bool
    {
        return $bloodStock->update($data);
    }

    public function delete(BloodStock $bloodStock): bool
    {
        return $bloodStock->delete();
    }

    public function getByBloodType(string $bloodType, string $rhesus): ?BloodStock
    {
        return $this->model
            ->where('blood_type', $bloodType)
            ->where('rhesus', $rhesus)
            ->first();
    }

    public function getLowStock(int $threshold = 5): Collection
    {
        return $this->model
            ->where('quantity', '<=', $threshold)
            ->orderBy('blood_type')
            ->orderBy('rhesus')
            ->get();
    }

    public function getExpiringSoon(int $days = 7): Collection
    {
        return $this->model
            ->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>=', now())
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date')
            ->get();
    }

    public function increaseStock(string $bloodType, string $rhesus, int $amount): bool
    {
        $stock = $this->getByBloodType($bloodType, $rhesus);
        
        if (!$stock) {
            return false;
        }

        return $stock->increment('quantity', $amount);
    }

    public function decreaseStock(string $bloodType, string $rhesus, int $amount): bool
    {
        $stock = $this->getByBloodType($bloodType, $rhesus);
        
        if (!$stock || $stock->quantity < $amount) {
            return false;
        }

        return $stock->decrement('quantity', $amount);
    }
}
