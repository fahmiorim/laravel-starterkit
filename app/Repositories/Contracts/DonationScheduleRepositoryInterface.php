<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface DonationScheduleRepositoryInterface
{
    public function all(): Collection;
    public function paginate(int $perPage = 10): LengthAwarePaginator;
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id): bool;
    public function getUpcomingSchedules(int $limit = 5): Collection;
    public function getPublishedSchedules(int $perPage = 10): LengthAwarePaginator;
    public function updateStatus(int $id, string $status): bool;
}
