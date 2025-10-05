<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\DonationSchedule;
use App\Models\Donor;
use App\Models\DonorHistory;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $summary = [
            'total_donors' => Donor::count(),
            'active_donors' => Donor::whereNotNull('last_donation_date')->count(),
            'scheduled_events' => DonationSchedule::where('status', 'published')->count(),
            'donations_this_month' => DonorHistory::whereMonth('tanggal_donor', Carbon::now()->month)
                ->whereYear('tanggal_donor', Carbon::now()->year)
                ->where('status', 'valid')
                ->sum('jumlah_kantong'),
        ];

        $recentRequests = BloodRequest::latest()->take(5)->get();

        return view('admin.reports.index', compact('summary', 'recentRequests'));
    }
}
