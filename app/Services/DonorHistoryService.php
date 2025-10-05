<?php

namespace App\Services;

use App\DTOs\DonorHistoryData;
use App\Events\DonorHistoryCreated;
use App\Events\DonorHistoryStatusChanged;
use App\Models\DonorHistory;
use App\Repositories\Contracts\DonorHistoryRepositoryInterface;
use App\Services\Contracts\DonorHistoryServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DonorHistoryService implements DonorHistoryServiceInterface
{
    public function __construct(
        protected DonorHistoryRepositoryInterface $repository
    ) {}

    public function getAllHistories(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->repository->paginate($perPage);

        if (!empty($filters['status'])) {
            $query = $this->repository->findByStatus($filters['status'], $perPage);
        }

        if (!empty($filters['donor'])) {
            $query = $this->repository->findByDonor((int) $filters['donor'], $perPage);
        }

        return $query;
    }

    public function getHistoryById(int $id): ?DonorHistory
    {
        return $this->repository->find($id);
    }

    public function createHistory(array $data): DonorHistory
    {
        $historyData = DonorHistoryData::fromArray($data);

        return DB::transaction(function () use ($historyData) {
            $history = $this->repository->create($historyData->toArray());

            DonorHistoryCreated::dispatch($history);
            $history->refreshDonorSnapshot();

            return $history;
        });
    }

    public function updateHistory(int $id, array $data): bool
    {
        $existing = $this->repository->find($id);

        if (!$existing) {
            return false;
        }

        $payload = array_merge([
            'donor_id' => $existing->donor_id,
            'blood_donation_schedule_id' => $existing->blood_donation_schedule_id,
            'tanggal_donor' => $existing->tanggal_donor->toDateString(),
            'lokasi' => $existing->lokasi,
            'jumlah_kantong' => $existing->jumlah_kantong,
            'status' => $existing->status,
            'note' => $existing->note,
        ], $data);

        $historyData = DonorHistoryData::fromArray($payload);

        return DB::transaction(function () use ($id, $historyData, $existing) {
            $updated = $this->repository->update($id, $historyData->toArray());

            if ($updated) {
                if ($existing->status !== $historyData->status) {
                    DonorHistoryStatusChanged::dispatch($existing, $existing->status, $historyData->status);
                }

                $history = $this->repository->find($id);
                if ($history) {
                    $history->refreshDonorSnapshot();
                }
            }

            return $updated;
        });
    }

    public function deleteHistory(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $history = $this->repository->find($id);

            if (!$history) {
                return false;
            }

            $deleted = $this->repository->delete($id);

            if ($deleted) {
                $history->refreshDonorSnapshot();
            }

            return $deleted;
        });
    }

    public function getHistoriesByDonor(int $donorId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->findByDonor($donorId, $perPage);
    }

    public function getHistoriesByStatus(string $status, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->findByStatus($status, $perPage);
    }
}
