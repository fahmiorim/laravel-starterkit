<?php

namespace App\Repositories\Eloquent;

use App\Models\BloodRequest;
use App\Repositories\Contracts\BloodRequestRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BloodRequestRepository implements BloodRequestRepositoryInterface
{
    protected $model;

    public function __construct(BloodRequest $bloodRequest)
    {
        $this->model = $bloodRequest;
    }

    public function all(): Collection
    {
        return $this->model->with('processor')->get();
    }

    public function find(int $id): ?BloodRequest
    {
        return $this->model->with('processor')->findOrFail($id);
    }

    public function create(array $data): BloodRequest
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->find($id)->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('processor')
            ->latest()
            ->paginate($perPage);
    }

    public function getByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)
            ->with('processor')
            ->latest()
            ->get();
    }

    public function getRecentRequests(int $limit = 5): Collection
    {
        return $this->model->with('processor')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function updateStatus(int $id, string $status, ?int $processedBy = null): bool
    {
        $data = ['status' => $status];
        
        if ($processedBy) {
            $data['processed_by'] = $processedBy;
        }
        
        return $this->update($id, $data);
    }
}
