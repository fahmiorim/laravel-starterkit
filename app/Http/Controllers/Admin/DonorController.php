<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Services\KtaService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DonorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $donors = Donor::latest()->paginate(10);

        return view('admin.donors.index', compact('donors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.donors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, KtaService $ktaService)
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

        $donor = Donor::create($validated);

        $ktaService->ensureKtaNumber($donor);

        return redirect()
            ->route('admin.donors.index')
            ->with('success', 'Data pendonor berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Donor $donor)
    {
        return redirect()->route('admin.donors.edit', $donor->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donor $donor)
    {
        $histories = $donor->histories()->with('schedule')->latest('tanggal_donor')->take(10)->get();

        return view('admin.donors.edit', compact('donor', 'histories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Donor $donor, KtaService $ktaService)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'size:16', Rule::unique('donors')->ignore($donor->id)],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'birth_date' => ['required', 'date'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:20'],
            'blood_type' => ['required', 'in:A,B,AB,O'],
            'rhesus' => ['required', 'in:+,-'],
        ]);

        $donor->update($validated);

        $ktaService->ensureKtaNumber($donor);

        return redirect()
            ->route('admin.donors.index')
            ->with('success', 'Data pendonor berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donor $donor)
    {
        $donor->delete();

        return redirect()
            ->route('admin.donors.index')
            ->with('success', 'Data pendonor berhasil dihapus.');
    }

    /**
     * Generate KTA for the specified resource.
     */
    public function generateKta(Donor $donor, KtaService $ktaService)
    {
        $pdf = $ktaService->generate($donor);

        return $pdf->stream('kta-' . $donor->kta_number . '.pdf');
    }
}
