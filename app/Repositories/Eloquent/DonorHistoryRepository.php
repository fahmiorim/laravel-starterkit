<?php

namespace App\Repositories\Eloquent;

use App\Models\DonorHistory;
use App\Repositories\Contracts\DonorHistoryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class DonorHistoryRepository implements DonorHistoryRepositoryInterface
{
    protected $model;

    public function __construct(DonorHistory $donorHistory)
    {
        $this->model = $donorHistory;
    }

    public function find(int $id): ?DonorHistory
    {
        return $this->model->with(['donor', 'schedule'])->find($id);
    }

    public function create(array $data): DonorHistory
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->find($id)?->update($data) ?? false;
    }

    public function delete(int $id): bool
    {
        return $this->find($id)?->delete() ?? false;
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['donor', 'schedule'])
            ->latest('tanggal_donor')
            ->paginate($perPage);
    }

    public function findByDonor(int $donorId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['donor', 'schedule'])
            ->where('donor_id', $donorId)
            ->latest('tanggal_donor')
            ->paginate($perPage);
    }

    public function findByStatus(string $status, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['donor', 'schedule'])
            ->where('status', $status)
            ->latest('tanggal_donor')
            ->paginate($perPage);
    }

    public function getWithRelations(int $id): ?DonorHistory
    {
        return $this->model->with(['donor', 'schedule'])->find($id);
    }
}
