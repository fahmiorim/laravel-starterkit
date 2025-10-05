<?php

namespace App\Services;

use App\Events\DonorCreated;
use App\Events\DonorUpdated;
use App\Events\DonorEligibilityChecked;
use App\Models\Donor;
use App\Repositories\Contracts\DonorRepositoryInterface;
use App\Services\Contracts\DonorServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DonorService implements DonorServiceInterface
{
    protected $donorRepository;

    public function __construct(DonorRepositoryInterface $donorRepository)
    {
        $this->donorRepository = $donorRepository;
    }

    /**
     * Mendapatkan semua data donor
     */
    public function getAllDonors(): Collection
    {
        return $this->donorRepository->all();
    }

    /**
     * Mendapatkan data donor dengan pagination
     */
    public function getPaginatedDonors(int $perPage = 15): LengthAwarePaginator
    {
        return $this->donorRepository->paginate($perPage);
    }

    /**
     * Mencari donor berdasarkan query
     */
    public function searchDonors(string $query): Collection
    {
        return $this->donorRepository->search($query);
    }

    /**
     * Mendapatkan data donor berdasarkan ID
     */
    public function getDonorById(int $id): ?Donor
    {
        return $this->donorRepository->find($id);
    }

    /**
     * Mendaftarkan donor baru
     */
    public function registerDonor(array $data): Donor
    {
        return DB::transaction(function () use ($data) {
            // Format tanggal lahir
            if (isset($data['birth_date'])) {
                $data['birth_date'] = Carbon::createFromFormat('d/m/Y', $data['birth_date'])->format('Y-m-d');
            }

            // Cek apakah donor sudah terdaftar berdasarkan NIK
            $existingDonor = $this->donorRepository->findByNik($data['nik']);
            
            if ($existingDonor) {
                throw new \Exception('Donor dengan NIK tersebut sudah terdaftar.');
            }

            $donor = $this->donorRepository->create($data);
            
            // Dispatch event
            event(new DonorCreated($donor));
            
            return $donor;
        });
    }

    /**
     * Memperbarui data donor
     */
    public function updateDonor(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            // Format tanggal lahir jika ada
            if (isset($data['birth_date'])) {
                $data['birth_date'] = Carbon::createFromFormat('d/m/Y', $data['birth_date'])->format('Y-m-d');
            }

            $donor = $this->donorRepository->find($id);
            
            if (!$donor) {
                return false;
            }

            $updated = $this->donorRepository->update($donor, $data);
            
            if ($updated) {
                event(new DonorUpdated($donor->fresh()));
            }
            
            return $updated;
        });
    }

    /**
     * Menghapus data donor
     */
    public function deleteDonor(int $id): bool
    {
        $donor = $this->donorRepository->find($id);
        
        if (!$donor) {
            return false;
        }

        return $this->donorRepository->delete($donor);
    }

    /**
     * Memeriksa kelayakan donor
     */
    public function checkDonorEligibility(int $donorId): array
    {
        $donor = $this->donorRepository->find($donorId);
        
        if (!$donor) {
            throw new \Exception('Data donor tidak ditemukan.');
        }

        $lastDonationDate = $this->donorRepository->getLastDonationDate($donorId);
        $isEligible = true;
        $reason = '';
        $nextEligibleDate = null;

        // Cek status donor
        if ($donor->status !== 'active') {
            $isEligible = false;
            $reason = 'Status donor tidak aktif.';
        }
        // Cek jarak donor terakhir
        elseif ($lastDonationDate) {
            $lastDonation = Carbon::parse($lastDonationDate);
            $nextEligibleDate = $lastDonation->copy()->addMonths(3);
            
            if ($nextEligibleDate->isFuture()) {
                $isEligible = false;
                $reason = 'Donor terakhir belum mencapai 3 bulan. Donor berikutnya bisa dilakukan setelah ' . $nextEligibleDate->format('d M Y');
            }
        }

        // Dispatch event
        event(new DonorEligibilityChecked($donor, $isEligible, $reason));

        return [
            'is_eligible' => $isEligible,
            'reason' => $reason,
            'last_donation_date' => $lastDonationDate,
            'donor' => $donor
        ];
    }

    /**
     * Mendapatkan riwayat donasi donor
     */
    public function getDonationHistory(int $donorId, int $limit = 10): Collection
    {
        return $this->donorRepository->getDonationHistory($donorId, $limit);
    }

    /**
     * Mendapatkan daftar donor yang memenuhi syarat untuk didonorkan
     */
    public function getEligibleDonors(): Collection
    {
        return $this->donorRepository->getEligibleDonors();
    }

    /**
     * Mendapatkan daftar donor berdasarkan golongan darah
     */
    public function getDonorsByBloodType(string $bloodType, string $rhesus): Collection
    {
        return $this->donorRepository->getDonorsByBloodType($bloodType, $rhesus);
    }

    /**
     * Mengaktifkan/menonaktifkan status donor
     */
    public function toggleDonorStatus(int $id, string $status): bool
    {
        $allowedStatuses = ['active', 'inactive', 'banned'];
        
        if (!in_array($status, $allowedStatuses)) {
            throw new \InvalidArgumentException('Status donor tidak valid.');
        }

        $donor = $this->donorRepository->find($id);
        
        if (!$donor) {
            return false;
        }

        return $this->donorRepository->update($donor, ['status' => $status]);
    }

    /**
     * Mendapatkan donor dengan filter
     */
    public function getDonorsWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Donor::query();

        if (isset($filters['blood_type']) && !empty($filters['blood_type'])) {
            $query->where('blood_type', $filters['blood_type']);
        }

        if (isset($filters['rhesus']) && !empty($filters['rhesus'])) {
            $query->where('rhesus', $filters['rhesus']);
        }

        if (isset($filters['status']) && !empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('nik', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->latest()->paginate($perPage);
    }
}
