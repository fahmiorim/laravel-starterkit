<?php

namespace App\Services;

use App\DTOs\BloodRequestData;
use App\Events\BloodRequestCreated;
use App\Events\BloodRequestStatusChanged;
use App\Models\BloodRequest;
use App\Repositories\Contracts\BloodRequestRepositoryInterface;
use App\Services\Contracts\BloodRequestServiceInterface;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BloodRequestService implements BloodRequestServiceInterface
{
    protected $repository;
    protected $notificationService;

    public function __construct(
        BloodRequestRepositoryInterface $repository,
        NotificationService $notificationService
    ) {
        $this->repository = $repository;
        $this->notificationService = $notificationService;
    }

    public function getAllRequests(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function getRequestById(int $id): ?BloodRequest
    {
        return $this->repository->find($id);
    }

    public function createRequest(array $data, int $processedBy): BloodRequest
    {
        $dto = BloodRequestData::fromArray($data, $processedBy);

        return DB::transaction(function () use ($dto) {
            $request = $this->repository->create($dto->toArray());

            $this->dispatchEvents($request);

            return $request;
        });
    }

    public function updateRequest(int $id, array $data): bool
    {
        if (array_key_exists('required_date', $data) && $data['required_date']) {
            $data['required_date'] = Carbon::parse($data['required_date']);
        }

        return $this->repository->update($id, $data);
    }

    public function deleteRequest(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function updateRequestStatus(int $id, string $status, ?int $processedBy = null): BloodRequest
    {
        return DB::transaction(function () use ($id, $status, $processedBy) {
            $this->repository->updateStatus($id, $status, $processedBy);

            $request = $this->getRequestById($id);
            $this->dispatchEvents($request, true);

            return $request;
        });
    }

    protected function dispatchEvents(BloodRequest $request, bool $isStatusUpdate = false): void
    {
        if ($isStatusUpdate) {
            $this->notificationService->notifyBloodRequestStatus($request);
            event(new BloodRequestStatusChanged($request));
        } else {
            $this->notificationService->notifyBloodRequestStatus($request);
            event(new BloodRequestCreated($request));
        }
    }

    public function getRequestsByStatus(string $status): Collection
    {
        return $this->repository->getByStatus($status);
    }

    public function getRecentRequests(int $limit = 5): Collection
    {
        return $this->repository->getRecentRequests($limit);
    }
}
