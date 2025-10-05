<?php

namespace App\Repositories\Eloquent;

use App\Models\DonationSchedule;
use App\Repositories\Contracts\DonationScheduleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class DonationScheduleRepository implements DonationScheduleRepositoryInterface
{
    protected $model;

    public function __construct(DonationSchedule $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->latest()->paginate($perPage);
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $schedule = $this->find($id);
        $schedule->update($data);
        return $schedule;
    }

    public function delete(int $id): bool
    {
        $schedule = $this->find($id);
        return $schedule->delete();
    }

    public function getUpcomingSchedules(int $limit = 5): Collection
    {
        $now = now();
        
        $schedules = $this->model->where('status', 'published')
            ->where('tanggal_mulai', '>=', $now)
            ->orderBy('tanggal_mulai')
            ->limit($limit)
            ->get();

        if ($schedules->isEmpty()) {
            return $this->model->where('status', 'published')
                ->latest('tanggal_mulai')
                ->limit($limit)
                ->get();
        }

        return $schedules;
    }

    public function getPublishedSchedules(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->where('status', 'published')
            ->latest('tanggal_mulai')
            ->paginate($perPage);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $schedule = $this->find($id);
        return $schedule->update(['status' => $status]);
    }
}
