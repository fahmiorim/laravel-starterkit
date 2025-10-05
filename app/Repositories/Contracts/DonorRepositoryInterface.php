<?php

namespace App\Repositories\Contracts;

use App\Models\Donor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface DonorRepositoryInterface
{
    public function all(): Collection;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Donor;
    public function findByNik(string $nik): ?Donor;
    public function create(array $data): Donor;
    public function update(Donor $donor, array $data): bool;
    public function delete(Donor $donor): bool;
    public function getEligibleDonors(): Collection;
    public function getDonationHistory(int $donorId, int $limit = 10): Collection;
    public function search(string $query): Collection;
    public function getDonorsByBloodType(string $bloodType, string $rhesus): Collection;
    public function getLastDonationDate(int $donorId): ?string;
}
