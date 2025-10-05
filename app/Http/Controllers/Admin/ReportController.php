<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\DonorHistory;
use App\Services\DonationScheduleService;
use Carbon\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    protected $donationScheduleService;

    public function __construct(DonationScheduleService $donationScheduleService)
    {
        $this->donationScheduleService = $donationScheduleService;
    }

    public function index(): View
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        
        // Get summary statistics
        $summary = [
            'total_donors' => Donor::count(),
            'active_donors' => Donor::whereNotNull('last_donation_date')->count(),
            'scheduled_events' => $this->donationScheduleService->getPublishedSchedules(1)->total(),
            'donations_this_month' => DonorHistory::whereMonth('tanggal_donor', $currentMonth)
                ->whereYear('tanggal_donor', $currentYear)
                ->where('status', 'valid')
                ->sum('jumlah_kantong'),
            'blood_requests' => BloodRequest::count(),
            'completed_requests' => BloodRequest::where('status', 'completed')->count(),
        ];

        // Blood type distribution
        $bloodTypes = ['A', 'B', 'AB', 'O'];
        $rhesusTypes = ['+', '-'];
        $bloodTypeData = [];
        
        foreach ($bloodTypes as $type) {
            foreach ($rhesusTypes as $rhesus) {
                $count = Donor::where('blood_type', $type)
                    ->where('rhesus', $rhesus)
                    ->count();
                $bloodTypeData["{$type}{$rhesus}"] = $count;
            }
        }

        // Monthly donations for the current year
        $monthlyDonations = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyDonations[Carbon::create()->month($i)->monthName] = DonorHistory::whereMonth('tanggal_donor', $i)
                ->whereYear('tanggal_donor', $currentYear)
                ->where('status', 'valid')
                ->sum('jumlah_kantong');
        }

        $recentRequests = BloodRequest::latest()->take(5)->get();

        return view('admin.reports.index', compact(
            'summary', 
            'recentRequests', 
            'bloodTypeData', 
            'monthlyDonations'
        ));
    }
}
