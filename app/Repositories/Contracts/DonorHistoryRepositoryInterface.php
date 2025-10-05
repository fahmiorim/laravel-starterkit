<?php

namespace App\Repositories\Contracts;

use App\Models\DonorHistory;
use Illuminate\Pagination\LengthAwarePaginator;

interface DonorHistoryRepositoryInterface
{
    public function find(int $id): ?DonorHistory;

    public function create(array $data): DonorHistory;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function findByDonor(int $donorId, int $perPage = 15): LengthAwarePaginator;

    public function findByStatus(string $status, int $perPage = 15): LengthAwarePaginator;

    public function getWithRelations(int $id): ?DonorHistory;
}
