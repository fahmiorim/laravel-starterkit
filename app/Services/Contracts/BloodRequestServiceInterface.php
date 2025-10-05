<?php

namespace App\Services\Contracts;

use App\Models\BloodRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BloodRequestServiceInterface
{
    public function getAllRequests(int $perPage = 15): LengthAwarePaginator;
    public function getRequestById(int $id): ?BloodRequest;
    public function createRequest(array $data, int $processedBy): BloodRequest;
    public function updateRequest(int $id, array $data): bool;
    public function deleteRequest(int $id): bool;
    public function updateRequestStatus(int $id, string $status, ?int $processedBy = null): BloodRequest;
    public function getRequestsByStatus(string $status): Collection;
    public function getRecentRequests(int $limit = 5): Collection;
}
