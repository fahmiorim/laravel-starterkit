<?php

namespace App\Services\Contracts;

use App\Models\DonorHistory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface DonorHistoryServiceInterface
{
    public function getAllHistories(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function getHistoryById(int $id): ?DonorHistory;

    public function createHistory(array $data): DonorHistory;

    public function updateHistory(int $id, array $data): bool;

    public function deleteHistory(int $id): bool;

    public function getHistoriesByDonor(int $donorId, int $perPage = 15): LengthAwarePaginator;

    public function getHistoriesByStatus(string $status, int $perPage = 15): LengthAwarePaginator;
}
