<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonorHistoryRequest;
use App\Http\Requests\UpdateDonorHistoryRequest;
use App\Models\DonorHistory;
use App\Services\Contracts\DonorHistoryServiceInterface;
use App\Services\Contracts\DonorServiceInterface;
use App\Services\DonationScheduleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonorHistoryController extends Controller
{
    public function __construct(
        protected DonorHistoryServiceInterface $donorHistoryService,
        protected DonorServiceInterface $donorService,
        protected DonationScheduleService $donationScheduleService
    ) {}

    public function index(Request $request): View
    {
        $histories = $this->donorHistoryService->getAllHistories(
            $request->only(['status', 'donor']),
            15
        );

        $donors = $this->donorService->getAllDonors()
            ->sortBy('name')
            ->pluck('name', 'id');

        return view('admin.donor-histories.index', compact('histories', 'donors'));
    }

    public function create(Request $request): View
    {
        $donors = $this->donorService->getAllDonors()->sortBy('name');
        $schedules = $this->donationScheduleService->getAllSchedules()
            ->sortByDesc('tanggal_mulai');

        $donorHistory = new DonorHistory([
            'donor_id' => $request->integer('donor_id') ?: null,
        ]);

        return view('admin.donor-histories.create', compact('donors', 'schedules', 'donorHistory'));
    }

    public function store(StoreDonorHistoryRequest $request): RedirectResponse
    {
        $this->donorHistoryService->createHistory($request->validated());

        return redirect()->route('admin.donor-histories.index')
            ->with('success', 'Riwayat donor berhasil ditambahkan.');
    }

    public function show(int $id): View
    {
        $donorHistory = $this->donorHistoryService->getHistoryById($id);

        if (!$donorHistory) {
            abort(404);
        }

        return view('admin.donor-histories.show', compact('donorHistory'));
    }

    public function edit(int $id): View
    {
        $donorHistory = $this->donorHistoryService->getHistoryById($id);

        if (!$donorHistory) {
            abort(404);
        }

        $donors = $this->donorService->getAllDonors()->sortBy('name');
        $schedules = $this->donationScheduleService->getAllSchedules()
            ->sortByDesc('tanggal_mulai');

        return view('admin.donor-histories.edit', compact('donorHistory', 'donors', 'schedules'));
    }

    public function update(UpdateDonorHistoryRequest $request, int $id): RedirectResponse
    {
        $success = $this->donorHistoryService->updateHistory($id, $request->validated());

        if (!$success) {
            return back()->with('error', 'Gagal memperbarui riwayat donor.');
        }

        return redirect()->route('admin.donor-histories.index')
            ->with('success', 'Riwayat donor berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $success = $this->donorHistoryService->deleteHistory($id);

        if (!$success) {
            return back()->with('error', 'Gagal menghapus riwayat donor.');
        }

        return redirect()->route('admin.donor-histories.index')
            ->with('success', 'Riwayat donor berhasil dihapus.');
    }
}
