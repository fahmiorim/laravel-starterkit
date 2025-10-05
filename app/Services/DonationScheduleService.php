<?php

namespace App\Services;

use App\Events\DonationScheduleCreated;
use App\Events\DonationScheduleUpdated;
use App\Events\DonationScheduleStatusChanged;
use App\Repositories\Contracts\DonationScheduleRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DonationScheduleService
{
    protected $donationScheduleRepository;

    public function __construct(DonationScheduleRepositoryInterface $donationScheduleRepository)
    {
        $this->donationScheduleRepository = $donationScheduleRepository;
    }

    public function getAllSchedules(): Collection
    {
        return $this->donationScheduleRepository->all();
    }

    public function getPaginatedSchedules(int $perPage = 10): LengthAwarePaginator
    {
        return $this->donationScheduleRepository->paginate($perPage);
    }

    public function getScheduleById(int $id)
    {
        return $this->donationScheduleRepository->find($id);
    }

    public function createSchedule(array $data)
    {
        try {
            $schedule = $this->donationScheduleRepository->create($data);
            event(new DonationScheduleCreated($schedule));
            return $schedule;
        } catch (\Exception $e) {
            Log::error('Failed to create donation schedule: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateSchedule(int $id, array $data)
    {
        try {
            $schedule = $this->donationScheduleRepository->update($id, $data);
            event(new DonationScheduleUpdated($schedule));
            return $schedule;
        } catch (\Exception $e) {
            Log::error('Failed to update donation schedule: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteSchedule(int $id): bool
    {
        try {
            return $this->donationScheduleRepository->delete($id);
        } catch (\Exception $e) {
            Log::error('Failed to delete donation schedule: ' . $e->getMessage());
            throw $e;
        }
    }

    public function publishSchedule(int $id)
    {
        return $this->updateStatus($id, 'published');
    }

    public function cancelSchedule(int $id)
    {
        return $this->updateStatus($id, 'canceled');
    }

    public function getUpcomingSchedules(int $limit = 5): Collection
    {
        return $this->donationScheduleRepository->getUpcomingSchedules($limit);
    }

    public function getPublishedSchedules(int $perPage = 10): LengthAwarePaginator
    {
        return $this->donationScheduleRepository->getPublishedSchedules($perPage);
    }

    protected function updateStatus(int $id, string $status)
    {
        try {
            $schedule = $this->donationScheduleRepository->find($id);
            $oldStatus = $schedule->status;
            
            $this->donationScheduleRepository->updateStatus($id, $status);
            
            event(new DonationScheduleStatusChanged($schedule, $oldStatus, $status));
            
            return $schedule->fresh();
        } catch (\Exception $e) {
            Log::error("Failed to update schedule status: {$e->getMessage()}");
            throw $e;
        }
    }
}
