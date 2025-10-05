<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\DonorHistory;
use App\Models\DonationSchedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DonorHistoryController extends Controller
{
    public function index(Request $request)
    {
        $histories = DonorHistory::with(['donor', 'schedule'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('donor'), fn ($query) => $query->where('donor_id', $request->donor))
            ->latest('tanggal_donor')
            ->paginate(15)
            ->withQueryString();

        $donors = Donor::orderBy('name')->get(['id', 'name']);

        return view('admin.donor-histories.index', compact('histories', 'donors'));
    }

    public function create(Request $request)
    {
        $donors = Donor::orderBy('name')->get();
        $schedules = DonationSchedule::orderBy('tanggal_mulai', 'desc')->get();
        $donorHistory = new DonorHistory([
            'donor_id' => $request->integer('donor_id') ?: null,
        ]);

        return view('admin.donor-histories.create', compact('donors', 'schedules', 'donorHistory'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_id' => ['required', Rule::exists('donors', 'id')],
            'blood_donation_schedule_id' => ['nullable', Rule::exists('blood_donation_schedules', 'id')],
            'tanggal_donor' => ['required', 'date'],
            'lokasi' => ['required', 'string', 'max:255'],
            'jumlah_kantong' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['pending', 'valid', 'ditolak'])],
            'note' => ['nullable', 'string'],
        ]);

        DonorHistory::create($validated);

        return redirect()->route('admin.donor-histories.index')
            ->with('success', 'Riwayat donor berhasil ditambahkan.');
    }

    public function edit(DonorHistory $donorHistory)
    {
        $donors = Donor::orderBy('name')->get();
        $schedules = DonationSchedule::orderBy('tanggal_mulai', 'desc')->get();

        return view('admin.donor-histories.edit', compact('donorHistory', 'donors', 'schedules'));
    }

    public function update(Request $request, DonorHistory $donorHistory)
    {
        $validated = $request->validate([
            'donor_id' => ['required', Rule::exists('donors', 'id')],
            'blood_donation_schedule_id' => ['nullable', Rule::exists('blood_donation_schedules', 'id')],
            'tanggal_donor' => ['required', 'date'],
            'lokasi' => ['required', 'string', 'max:255'],
            'jumlah_kantong' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['pending', 'valid', 'ditolak'])],
            'note' => ['nullable', 'string'],
        ]);

        $donorHistory->update($validated);

        return redirect()->route('admin.donor-histories.index')
            ->with('success', 'Riwayat donor berhasil diperbarui.');
    }

    public function destroy(DonorHistory $donorHistory)
    {
        $donorHistory->delete();

        return redirect()->route('admin.donor-histories.index')
            ->with('success', 'Riwayat donor berhasil dihapus.');
    }
}
