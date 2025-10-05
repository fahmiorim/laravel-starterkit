<?php

namespace App\Http\Controllers;

use App\Services\DonationScheduleService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalDonorController extends Controller
{
    protected $donationScheduleService;

    public function __construct(DonationScheduleService $donationScheduleService)
    {
        $this->donationScheduleService = $donationScheduleService;
    }

    public function index(): View
    {
        $schedules = $this->donationScheduleService->getPublishedSchedules();
        return view('jadwal-donor.index', compact('schedules'));
    }

    public function show(int $id): View
    {
        $schedule = $this->donationScheduleService->getPublishedScheduleById($id);

        abort_if(!$schedule, 404);

        return view('jadwal-donor.show', compact('schedule'));
    }
}