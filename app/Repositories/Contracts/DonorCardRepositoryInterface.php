<?php

namespace App\Repositories\Contracts;

use App\Models\DonorCard;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface DonorCardRepositoryInterface
{
    public function all(): Collection;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?DonorCard;
    public function findByCardNumber(string $cardNumber): ?DonorCard;
    public function create(array $data): DonorCard;
    public function update(DonorCard $donorCard, array $data): bool;
    public function delete(DonorCard $donorCard): bool;
    public function getActiveCards(): Collection;
    public function getExpiringSoon(int $days = 7): Collection;
}
