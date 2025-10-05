<?php

namespace App\Repositories\Contracts;

use App\Models\BloodStock;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BloodStockRepositoryInterface
{
    public function all(): Collection;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?BloodStock;
    public function create(array $data): BloodStock;
    public function update(BloodStock $bloodStock, array $data): bool;
    public function delete(BloodStock $bloodStock): bool;
    public function getByBloodType(string $bloodType, string $rhesus): ?BloodStock;
    public function getLowStock(int $threshold = 5): Collection;
    public function getExpiringSoon(int $days = 7): Collection;
    public function increaseStock(string $bloodType, string $rhesus, int $amount): bool;
    public function decreaseStock(string $bloodType, string $rhesus, int $amount): bool;
}
