<?php

namespace App\Services\Contracts;

use App\Models\Donor;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface DonorServiceInterface
{
    public function getAllDonors(): Collection;

    public function getPaginatedDonors(int $perPage = 15): LengthAwarePaginator;

    public function searchDonors(string $query): Collection;

    public function getDonorById(int $id): ?Donor;

    public function registerDonor(array $data): Donor;

    public function updateDonor(int $id, array $data): bool;

    public function deleteDonor(int $id): bool;

    public function checkDonorEligibility(int $donorId): array;

    public function getDonationHistory(int $donorId, int $limit = 10): Collection;

    public function getEligibleDonors(): Collection;

    public function getDonorsByBloodType(string $bloodType, string $rhesus): Collection;

    public function toggleDonorStatus(int $id, string $status): bool;

    public function getDonorsWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator;
}
