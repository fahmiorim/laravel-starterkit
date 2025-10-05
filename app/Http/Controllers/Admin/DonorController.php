<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Services\Contracts\DonorServiceInterface;
use App\Services\KtaService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class DonorController extends Controller
{
    public function __construct(
        protected DonorServiceInterface $donorService,
        protected KtaService $ktaService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $donors = $this->donorService->getDonorsWithFilters(
            $request->only(['search', 'blood_type', 'rhesus', 'status']),
            10
        );

        return view('admin.donors.index', compact('donors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.donors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'size:16', 'unique:donors,nik'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'birth_date' => ['required', 'date'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:20'],
            'blood_type' => ['required', 'in:A,B,AB,O'],
            'rhesus' => ['required', 'in:+,-'],
        ]);

        $donor = $this->donorService->registerDonor($validated);

        $this->ktaService->ensureKtaNumber($donor);

        return redirect()
            ->route('admin.donors.index')
            ->with('success', 'Data pendonor berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $donor = $this->donorService->getDonorById($id);

        if (!$donor) {
            abort(404);
        }

        return view('admin.donors.show', compact('donor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $donor = $this->donorService->getDonorById($id);

        if (!$donor) {
            abort(404);
        }

        return view('admin.donors.edit', compact('donor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'size:16', Rule::unique('donors')->ignore($id)],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'birth_date' => ['required', 'date'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:20'],
            'blood_type' => ['required', 'in:A,B,AB,O'],
            'rhesus' => ['required', 'in:+,-'],
        ]);

        $success = $this->donorService->updateDonor($id, $validated);

        if (!$success) {
            return back()->with('error', 'Gagal memperbarui data donor.');
        }

        $donor = $this->donorService->getDonorById($id);
        if ($donor) {
            $this->ktaService->ensureKtaNumber($donor);
        }

        return redirect()
            ->route('admin.donors.index')
            ->with('success', 'Data pendonor berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $success = $this->donorService->deleteDonor($id);

        if (!$success) {
            return back()->with('error', 'Gagal menghapus data donor.');
        }

        return redirect()
            ->route('admin.donors.index')
            ->with('success', 'Data pendonor berhasil dihapus.');
    }

    /**
     * Check donor eligibility.
     */
    public function checkEligibility(int $id): RedirectResponse
    {
        try {
            $eligibility = $this->donorService->checkDonorEligibility($id);

            $message = $eligibility['is_eligible']
                ? 'Donor layak untuk melakukan donor darah.'
                : $eligibility['reason'];

            return back()->with($eligibility['is_eligible'] ? 'success' : 'warning', $message);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Toggle donor status.
     */
    public function toggleStatus(int $id, string $status): RedirectResponse
    {
        $allowedStatuses = ['active', 'inactive', 'banned'];

        if (!in_array($status, $allowedStatuses)) {
            return back()->with('error', 'Status tidak valid.');
        }

        $success = $this->donorService->toggleDonorStatus($id, $status);

        if (!$success) {
            return back()->with('error', 'Gagal mengubah status donor.');
        }

        return back()->with('success', 'Status donor berhasil diubah.');
    }

    /**
     * Generate KTA for the specified resource.
     */
    public function generateKta(int $id): \Symfony\Component\HttpFoundation\Response
    {
        $donor = $this->donorService->getDonorById($id);

        if (!$donor) {
            abort(404);
        }

        $pdf = $this->ktaService->generate($donor);

        return $pdf->stream('kta-' . $donor->kta_number . '.pdf');
    }
}
