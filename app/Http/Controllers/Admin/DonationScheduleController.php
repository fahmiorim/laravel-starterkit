<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DonationScheduleRequest;
use App\Services\DonationScheduleService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DonationScheduleController extends Controller
{
    protected $donationScheduleService;

    public function __construct(DonationScheduleService $donationScheduleService)
    {
        $this->donationScheduleService = $donationScheduleService;
    }

    public function index(): View
    {
        $schedules = $this->donationScheduleService->getPaginatedSchedules();

        return view('admin.schedules.index', compact('schedules'));
    }

    public function create(): View
    {
        return view('admin.schedules.create');
    }

    public function store(DonationScheduleRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $this->donationScheduleService->createSchedule($data);

            return redirect()
                ->route('admin.schedules.index')
                ->with('success', 'Jadwal donor darah berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating donation schedule: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan jadwal donor darah. Silakan coba lagi.');
        }
    }

    public function show(int $id): View
    {
        $schedule = $this->donationScheduleService->getScheduleById($id);

        return view('admin.schedules.show', compact('schedule'));
    }

    public function edit(int $id): View
    {
        $schedule = $this->donationScheduleService->getScheduleById($id);

        return view('admin.schedules.edit', compact('schedule'));
    }

    public function update(DonationScheduleRequest $request, int $id): RedirectResponse
    {
        try {
            $data = $request->validated();
            $this->donationScheduleService->updateSchedule($id, $data);

            return redirect()
                ->route('admin.schedules.show', $id)
                ->with('success', 'Jadwal donor darah berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating donation schedule: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui jadwal donor darah. Silakan coba lagi.');
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->donationScheduleService->deleteSchedule($id);

            return redirect()
                ->route('admin.schedules.index')
                ->with('success', 'Jadwal donor darah berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting donation schedule: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus jadwal donor darah. Silakan coba lagi.');
        }
    }

    public function publish(int $id): RedirectResponse
    {
        try {
            $schedule = $this->donationScheduleService->publishSchedule($id);

            return redirect()
                ->route('admin.schedules.show', $schedule->id)
                ->with('success', 'Jadwal donor darah berhasil dipublikasikan.');
        } catch (\Exception $e) {
            Log::error('Error publishing schedule: ' . $e->getMessage());
            return back()->with('error', 'Gagal mempublikasikan jadwal. Silakan coba lagi.');
        }
    }

    public function cancel(int $id): RedirectResponse
    {
        try {
            $schedule = $this->donationScheduleService->cancelSchedule($id);

            return redirect()
                ->route('admin.schedules.show', $schedule->id)
                ->with('success', 'Jadwal donor darah berhasil dibatalkan.');
        } catch (\Exception $e) {
            Log::error('Error canceling schedule: ' . $e->getMessage());
            return back()->with('error', 'Gagal membatalkan jadwal. Silakan coba lagi.');
        }
    }
}
