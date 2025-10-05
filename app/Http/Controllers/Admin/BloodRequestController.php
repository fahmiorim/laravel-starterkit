<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\BloodStock;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BloodRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = BloodRequest::with('processor')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.blood-requests.index', compact('requests'));
    }

    public function create()
    {
        return view('admin.blood-requests.create');
    }

    public function store(Request $request, NotificationService $notificationService)
    {
        $validated = $this->validatePayload($request);
        $validated['processed_by'] = $request->user()->id;

        $record = BloodRequest::create($validated);

        $notificationService->notifyBloodRequestStatus($record);

        if (in_array($record->status, ['approved', 'completed'], true)) {
            $this->reserveStock($record, $record->quantity);
        }

        return redirect()->route('admin.blood-requests.index')
            ->with('success', 'Permintaan darah berhasil dicatat.');
    }

    public function edit(BloodRequest $bloodRequest)
    {
        return view('admin.blood-requests.edit', compact('bloodRequest'));
    }

    public function update(Request $request, BloodRequest $bloodRequest, NotificationService $notificationService)
    {
        $validated = $this->validatePayload($request);

        $validated['processed_by'] = $request->user()->id;
        $previousStatus = $bloodRequest->status;

        $bloodRequest->update($validated);

        if (! in_array($previousStatus, ['approved', 'completed'], true)
            && in_array($bloodRequest->status, ['approved', 'completed'], true)) {
            $this->reserveStock($bloodRequest, $bloodRequest->quantity);
        }

        $notificationService->notifyBloodRequestStatus($bloodRequest);

        return redirect()->route('admin.blood-requests.index')
            ->with('success', 'Permintaan darah berhasil diperbarui.');
    }

    public function destroy(BloodRequest $bloodRequest)
    {
        $bloodRequest->delete();

        return redirect()->route('admin.blood-requests.index')
            ->with('success', 'Permintaan darah berhasil dihapus.');
    }

    protected function validatePayload(Request $request): array
    {
        return $request->validate([
            'hospital_name' => ['required', 'string', 'max:255'],
            'patient_name' => ['required', 'string', 'max:255'],
            'blood_type' => ['required', Rule::in(['A', 'B', 'AB', 'O'])],
            'rhesus' => ['required', Rule::in(['+', '-'])],
            'quantity' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['pending', 'approved', 'rejected', 'completed'])],
            'notes' => ['nullable', 'string'],
        ]);
    }

    protected function reserveStock(BloodRequest $record, int $requestedQuantity): void
    {
        $stock = BloodStock::where('blood_type', $record->blood_type)
            ->where('rhesus', $record->rhesus)
            ->first();

        if (! $stock) {
            return;
        }

        $remaining = max(0, $stock->quantity - $requestedQuantity);

        $stock->update([
            'quantity' => $remaining,
            'updated_by' => $record->processed_by,
        ]);
    }
}











