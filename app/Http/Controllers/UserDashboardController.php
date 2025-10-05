<?php

namespace App\Http\Controllers;

use App\Models\BloodStock;
use App\Models\DonationSchedule;
use App\Models\Donor;
use App\Models\DonorHistory;
use Carbon\Carbon;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function __invoke(): View
    {
        $now = Carbon::now();

        $upcomingSchedules = DonationSchedule::where('status', 'published')
            ->where('tanggal_mulai', '>=', $now)
            ->orderBy('tanggal_mulai')
            ->take(3)
            ->get();

        if ($upcomingSchedules->isEmpty()) {
            $upcomingSchedules = DonationSchedule::where('status', 'published')
                ->latest('tanggal_mulai')
                ->take(3)
                ->get();
        }

        $recentHistories = DonorHistory::with('donor', 'schedule')
            ->where('status', 'valid')
            ->latest('tanggal_donor')
            ->take(5)
            ->get();

        $stats = [
            'totalDonors' => Donor::count(),
            'donationsThisMonth' => DonorHistory::where('status', 'valid')
                ->whereBetween('tanggal_donor', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
                ->sum('jumlah_kantong'),
            'upcomingCount' => $upcomingSchedules->count(),
            'bloodStocks' => BloodStock::orderBy('blood_type')->orderBy('rhesus')->get(),
        ];

        return view('dashboard.index', compact('upcomingSchedules', 'recentHistories', 'stats'));
    }
}
