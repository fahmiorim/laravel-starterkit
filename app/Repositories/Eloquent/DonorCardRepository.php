<?php

namespace App\Repositories\Eloquent;

use App\Models\DonorCard;
use App\Repositories\Contracts\DonorCardRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class DonorCardRepository implements DonorCardRepositoryInterface
{
    protected $model;

    public function __construct(DonorCard $donorCard)
    {
        $this->model = $donorCard;
    }

    public function all(): Collection
    {
        return $this->model->with('donor')->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('donor')
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id): ?DonorCard
    {
        return $this->model->with('donor')->find($id);
    }

    public function findByCardNumber(string $cardNumber): ?DonorCard
    {
        return $this->model->where('card_number', $cardNumber)
            ->with('donor')
            ->first();
    }

    public function create(array $data): DonorCard
    {
        return $this->model->create($data);
    }

    public function update(DonorCard $donorCard, array $data): bool
    {
        return $donorCard->update($data);
    }

    public function delete(DonorCard $donorCard): bool
    {
        return $donorCard->delete();
    }

    public function getActiveCards(): Collection
    {
        return $this->model->where('status', 'active')
            ->where('expiry_date', '>=', now())
            ->with('donor')
            ->get();
    }

    public function getExpiringSoon(int $days = 7): Collection
    {
        return $this->model->where('expiry_date', '>=', now())
            ->where('expiry_date', '<=', now()->addDays($days))
            ->where('status', 'active')
            ->with('donor')
            ->get();
    }
}
