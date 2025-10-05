<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\BloodStock;
use App\Models\DonationSchedule;
use App\Models\Donor;
use App\Models\DonorHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $now = Carbon::now();

        $stats = [
            'totalUsers' => User::count(),
            'totalDonors' => Donor::count(),
            'activeDonors' => Donor::whereNotNull('last_donation_date')->count(),
            'publishedSchedules' => DonationSchedule::where('status', 'published')->count(),
            'pendingRequests' => BloodRequest::where('status', 'pending')->count(),
            'bloodStockBags' => BloodStock::sum('quantity'),
            'donationsThisMonth' => DonorHistory::where('status', 'valid')
                ->whereBetween('tanggal_donor', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
                ->sum('jumlah_kantong'),
        ];

        $upcomingSchedules = DonationSchedule::where('status', 'published')
            ->where('tanggal_mulai', '>=', $now)
            ->orderBy('tanggal_mulai')
            ->take(5)
            ->get();

        if ($upcomingSchedules->isEmpty()) {
            $upcomingSchedules = DonationSchedule::where('status', 'published')
                ->latest('tanggal_mulai')
                ->take(5)
                ->get();
        }

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
