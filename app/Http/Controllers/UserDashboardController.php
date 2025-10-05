<?php

namespace App\Http\Controllers;

use App\Models\BloodStock;
use App\Models\Donor;
use App\Models\DonorHistory;
use App\Services\DonationScheduleService;
use Carbon\Carbon;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    protected $donationScheduleService;

    public function __construct(DonationScheduleService $donationScheduleService)
    {
        $this->donationScheduleService = $donationScheduleService;
    }

    public function __invoke(): View
    {
        $now = Carbon::now();

        // Get upcoming schedules using the service
        $upcomingSchedules = $this->donationScheduleService->getUpcomingSchedules(3);

        // Get recent donation histories
        $recentHistories = DonorHistory::with('donor', 'schedule')
            ->where('status', 'valid')
            ->latest('tanggal_donor')
            ->take(5)
            ->get();

        // Prepare dashboard statistics
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

        return view('dashboard.index', compact('upcomingSchedules', 'recentHistories', 'stats'));
    }
}
