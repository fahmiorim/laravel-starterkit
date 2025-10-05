<?php

namespace App\Repositories\Eloquent;

use App\Models\Donor;
use App\Models\DonorHistory;
use App\Repositories\Contracts\DonorRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DonorRepository implements DonorRepositoryInterface
{
    protected $model;

    public function __construct(Donor $donor)
    {
        $this->model = $donor;
    }

    public function all(): Collection
    {
        return $this->model->with(['histories', 'lastDonation'])->latest()->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['histories', 'lastDonation'])
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id): ?Donor
    {
        return $this->model->with(['histories', 'lastDonation'])->find($id);
    }

    public function findByNik(string $nik): ?Donor
    {
        return $this->model->where('nik', $nik)->first();
    }

    public function create(array $data): Donor
    {
        return $this->model->create($data);
    }

    public function update(Donor $donor, array $data): bool
    {
        return $donor->update($data);
    }

    public function delete(Donor $donor): bool
    {
        return $donor->delete();
    }

    public function getEligibleDonors(): Collection
    {
        $threeMonthsAgo = now()->subMonths(3);
        
        return $this->model->whereDoesntHave('histories', function (Builder $query) use ($threeMonthsAgo) {
                $query->where('tanggal_donor', '>=', $threeMonthsAgo);
            })
            ->where('status', 'active')
            ->with(['lastDonation'])
            ->get();
    }

    public function getDonationHistory(int $donorId, int $limit = 10): Collection
    {
        return DonorHistory::where('donor_id', $donorId)
            ->with('bloodType')
            ->latest('tanggal_donor')
            ->limit($limit)
            ->get();
    }

    public function search(string $query): Collection
    {
        return $this->model->where('name', 'like', "%{$query}%")
            ->orWhere('nik', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->with(['lastDonation'])
            ->get();
    }

    public function getDonorsByBloodType(string $bloodType, string $rhesus): Collection
    {
        return $this->model->where('blood_type', $bloodType)
            ->where('rhesus', $rhesus)
            ->where('status', 'active')
            ->with('lastDonation')
            ->get();
    }

    public function getLastDonationDate(int $donorId): ?string
    {
        $lastDonation = DonorHistory::where('donor_id', $donorId)
            ->latest('tanggal_donor')
            ->first();

        return $lastDonation ? $lastDonation->tanggal_donor : null;
    }
}
