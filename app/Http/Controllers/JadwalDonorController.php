<?php

namespace App\Http\Controllers;

use App\Models\DonationSchedule;
use Illuminate\Http\Request;

class JadwalDonorController extends Controller
{
    public function index()
    {
        $schedules = DonationSchedule::where('status', 'published')
            ->latest()
            ->paginate(10);

        return view('jadwal-donor.index', compact('schedules'));
    }

    public function show(DonationSchedule $schedule)
    {
        if ($schedule->status !== 'published') {
            abort(404);
        }

        return view('jadwal-donor.show', compact('schedule'));
    }
}