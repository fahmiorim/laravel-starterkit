<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonationSchedule;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class DonationScheduleController extends Controller
{
    public function index()
    {
        $schedules = DonationSchedule::latest()->paginate(10);

        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('admin.schedules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'penanggung_jawab' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,canceled',
        ]);

        DonationSchedule::create($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal donor darah berhasil ditambahkan.');
    }

    public function show(DonationSchedule $schedule)
    {
        return view('admin.schedules.show', compact('schedule'));
    }

    public function edit(DonationSchedule $schedule)
    {
        return view('admin.schedules.edit', compact('schedule'));
    }

    public function update(Request $request, DonationSchedule $schedule)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'penanggung_jawab' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,canceled',
        ]);

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal donor darah berhasil diperbarui.');
    }

    public function destroy(DonationSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal donor darah berhasil dihapus.');
    }

    public function publish(DonationSchedule $schedule, NotificationService $notificationService)
    {
        $schedule->update(['status' => 'published']);

        $recipients = User::role('pendonor')->get();
        if ($recipients->isNotEmpty()) {
            $notificationService->notifySchedulePublished($recipients, $schedule);
        }

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal donor darah berhasil dipublikasikan.');
    }

    public function cancel(DonationSchedule $schedule)
    {
        $schedule->update(['status' => 'canceled']);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal donor darah berhasil dibatalkan.');
    }
}
