<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\BloodStock;
use App\Models\Donor;
use App\Models\DonorHistory;
use App\Models\User;
use App\Services\DonationScheduleService;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected $donationScheduleService;

    public function __construct(DonationScheduleService $donationScheduleService)
    {
        $this->donationScheduleService = $donationScheduleService;
    }

    public function index(): View
    {
        $now = Carbon::now();

        // Get statistics
        $stats = [
            'totalUsers' => User::count(),
            'totalDonors' => Donor::count(),
            'activeDonors' => Donor::whereNotNull('last_donation_date')->count(),
            'publishedSchedules' => $this->donationScheduleService->getPublishedSchedules(1)->total(),
            'pendingRequests' => BloodRequest::where('status', 'pending')->count(),
            'bloodStockBags' => BloodStock::sum('quantity'),
            'donationsThisMonth' => DonorHistory::where('status', 'valid')
                ->whereBetween('tanggal_donor', [
                    $now->copy()->startOfMonth(),
                    $now->copy()->endOfMonth()
                ])
                ->sum('jumlah_kantong'),
        ];

        // Get upcoming schedules using the service
        $upcomingSchedules = $this->donationScheduleService->getUpcomingSchedules(5);

        $recentRequests = BloodRequest::with('processor')
            ->latest()
            ->take(5)
            ->get();

        $recentHistories = DonorHistory::with('donor', 'schedule')
            ->where('status', 'valid')
            ->latest('tanggal_donor')
            ->take(5)
            ->get();

        $lowStocks = BloodStock::orderBy('quantity')->take(4)->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'upcomingSchedules',
            'recentRequests',
            'recentHistories',
            'lowStocks'
        ));
    }
}
