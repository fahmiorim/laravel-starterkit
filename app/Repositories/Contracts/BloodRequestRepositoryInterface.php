<?php

namespace App\Repositories\Contracts;

use App\Models\BloodRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BloodRequestRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?BloodRequest;
    public function create(array $data): BloodRequest;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function getByStatus(string $status): Collection;
    public function getRecentRequests(int $limit = 5): Collection;
    public function updateStatus(int $id, string $status, ?int $processedBy = null): bool;
}
