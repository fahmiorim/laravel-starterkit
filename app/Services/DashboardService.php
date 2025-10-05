<?php

namespace App\Services;

use App\Models\BloodRequest;
use App\Models\BloodStock;
use App\Models\Donor;
use App\Models\DonorHistory;
use App\Models\User;
use App\Services\Contracts\DashboardServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService implements DashboardServiceInterface
{
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): array
    {
        $now = Carbon::now();

        return [
            'totalUsers' => User::count(),
            'totalDonors' => Donor::count(),
            'activeDonors' => Donor::whereNotNull('last_donation_date')->count(),
            'pendingRequests' => BloodRequest::where('status', 'pending')->count(),
            'bloodStockBags' => BloodStock::sum('quantity'),
            'donationsThisMonth' => DonorHistory::where('status', 'valid')
                ->whereBetween('tanggal_donor', [
                    $now->copy()->startOfMonth(),
                    $now->copy()->endOfMonth()
                ])
                ->sum('jumlah_kantong'),
            'publishedSchedules' => $this->getPublishedSchedulesCount(),
        ];
    }

    /**
     * Get report statistics
     */
    public function getReportStats(string $period = 'monthly'): array
    {
        $now = Carbon::now();
        $currentYear = $now->year;
        $currentMonth = $now->month;

        // Base stats that are common between dashboard and reports
        $baseStats = $this->getDashboardStats();

        // Additional report-specific stats
        $additionalStats = [
            'blood_requests' => BloodRequest::count(),
            'completed_requests' => BloodRequest::where('status', 'completed')->count(),
            'total_donations' => DonorHistory::where('status', 'valid')->sum('jumlah_kantong'),
            'donations_this_year' => DonorHistory::where('status', 'valid')
                ->whereYear('tanggal_donor', $currentYear)
                ->sum('jumlah_kantong'),
        ];

        // Period-specific calculations
        if ($period === 'monthly') {
            $additionalStats['donations_this_month'] = DonorHistory::whereMonth('tanggal_donor', $currentMonth)
                ->whereYear('tanggal_donor', $currentYear)
                ->where('status', 'valid')
                ->sum('jumlah_kantong');
        } elseif ($period === 'quarterly') {
            $quarterStart = $now->copy()->startOfQuarter();
            $quarterEnd = $now->copy()->endOfQuarter();
            $additionalStats['donations_this_quarter'] = DonorHistory::where('status', 'valid')
                ->whereBetween('tanggal_donor', [$quarterStart, $quarterEnd])
                ->sum('jumlah_kantong');
        }

        return array_merge($baseStats, $additionalStats);
    }

    /**
     * Get upcoming schedules
     */
    public function getUpcomingSchedules(int $limit = 5): Collection
    {
        return \App\Models\DonationSchedule::where('tanggal_mulai', '>=', Carbon::now())
            ->where('status', 'published')
            ->orderBy('tanggal_mulai')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent requests
     */
    public function getRecentRequests(int $limit = 5): Collection
    {
        return BloodRequest::with('processor')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get blood type distribution
     */
    public function getBloodTypeDistribution(): array
    {
        $bloodTypes = ['A', 'B', 'AB', 'O'];
        $rhesusTypes = ['+', '-'];
        $distribution = [];

        foreach ($bloodTypes as $type) {
            foreach ($rhesusTypes as $rhesus) {
                $count = Donor::where('blood_type', $type)
                    ->where('rhesus', $rhesus)
                    ->count();
                $distribution["{$type}{$rhesus}"] = $count;
            }
        }

        return $distribution;
    }

    /**
     * Get donation trends over time
     */
    public function getDonationTrends(int $months = 12): array
    {
        $trends = [];
        $now = Carbon::now();

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $count = DonorHistory::where('status', 'valid')
                ->whereBetween('tanggal_donor', [$monthStart, $monthEnd])
                ->sum('jumlah_kantong');

            $trends[] = [
                'month' => $date->format('Y-m'),
                'count' => $count,
                'label' => $date->format('M Y')
            ];
        }

        return $trends;
    }

    /**
     * Get system health metrics
     */
    public function getSystemHealth(): array
    {
        return [
            'total_errors_today' => \Illuminate\Support\Facades\Log::whereDate('created_at', Carbon::today())->count(),
            'database_connections' => DB::connection()->getPdo() ? 'healthy' : 'unhealthy',
            'cache_status' => Cache::store()->getStore() instanceof \Illuminate\Cache\TaggedCache ? 'healthy' : 'unhealthy',
            'storage_usage' => $this->getStorageUsage(),
            'memory_usage' => $this->getMemoryUsage(),
        ];
    }

    /**
     * Get user activity statistics
     */
    public function getUserActivityStats(): array
    {
        $now = Carbon::now();

        return [
            'active_users_today' => User::where('last_activity', '>=', $now->subDay())->count(),
            'new_users_this_month' => User::where('created_at', '>=', $now->startOfMonth())->count(),
            'admin_users' => User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->count(),
            'regular_users' => User::whereDoesntHave('roles')->count(),
        ];
    }

    /**
     * Get recent donor histories
     */
    public function getRecentHistories(int $limit = 5): Collection
    {
        return DonorHistory::with(['donor', 'schedule'])
            ->where('status', 'valid')
            ->latest('tanggal_donor')
            ->limit($limit)
            ->get();
    }

    /**
     * Get low stock blood types
     */
    public function getLowStock(int $threshold = 5): Collection
    {
        return BloodStock::where('quantity', '<=', $threshold)
            ->orderBy('quantity', 'asc')
            ->get();
    }

    /**
     * Helper method to get published schedules count
     */
    private function getPublishedSchedulesCount(): int
    {
        return \App\Models\DonationSchedule::where('status', 'published')->count();
    }

    /**
     * Helper method to get storage usage
     */
    private function getStorageUsage(): array
    {
        $storagePath = storage_path();
        $totalSpace = disk_total_space($storagePath);
        $freeSpace = disk_free_space($storagePath);
        $usedSpace = $totalSpace - $freeSpace;

        return [
            'total' => $totalSpace,
            'used' => $usedSpace,
            'free' => $freeSpace,
            'percentage' => round(($usedSpace / $totalSpace) * 100, 2)
        ];
    }

    /**
     * Helper method to get memory usage
     */
    private function getMemoryUsage(): array
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->parseMemoryLimit(ini_get('memory_limit'));

        return [
            'current' => $memoryUsage,
            'limit' => $memoryLimit,
            'percentage' => $memoryLimit > 0 ? round(($memoryUsage / $memoryLimit) * 100, 2) : 0
        ];
    }

    /**
     * Helper method to parse memory limit string
     */
    private function parseMemoryLimit(string $memoryLimit): int
    {
        $unit = strtolower(substr($memoryLimit, -1));
        $value = (int) substr($memoryLimit, 0, -1);

        return match ($unit) {
            'g' => $value * 1024 * 1024 * 1024,
            'm' => $value * 1024 * 1024,
            'k' => $value * 1024,
            default => (int) $memoryLimit
        };
    }
    /**
     * Get recently registered users.
     */
    public function getRecentUsers(int $limit = 5): Collection
    {
        return User::latest()->limit($limit)->get();
    }

    /**
     * Aggregate data needed for the user dashboard.
     */
    public function getUserDashboardData(int $upcomingLimit = 3, int $historyLimit = 5): array
    {
        $upcomingSchedules = $this->getUpcomingSchedules($upcomingLimit);

        $recentHistories = DonorHistory::with(['donor', 'schedule'])
            ->where('status', 'valid')
            ->latest('tanggal_donor')
            ->limit($historyLimit)
            ->get();

        $now = Carbon::now();

        $stats = [
            'totalDonors' => Donor::count(),
            'donationsThisMonth' => DonorHistory::where('status', 'valid')
                ->whereBetween('tanggal_donor', [
                    $now->copy()->startOfMonth(),
                    $now->copy()->endOfMonth()
                ])
                ->sum('jumlah_kantong'),
            'upcomingCount' => $upcomingSchedules->count(),
            'bloodStocks' => BloodStock::orderBy('blood_type')
                ->orderBy('rhesus')
                ->get(),
        ];

        return [
            'upcomingSchedules' => $upcomingSchedules,
            'recentHistories' => $recentHistories,
            'stats' => $stats,
        ];
    }
}
