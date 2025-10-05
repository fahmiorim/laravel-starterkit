<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBloodRequestRequest;
use App\Http\Requests\UpdateBloodRequestRequest;
use App\Http\Requests\UpdateBloodRequestStatusRequest;
use App\Services\Contracts\BloodRequestServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BloodRequestController extends Controller
{
    public function __construct(
        protected BloodRequestServiceInterface $bloodRequestService
    ) {
        $this->middleware('auth');
    }

    public function index(Request $request): View
    {
        $requests = $this->bloodRequestService->getAllRequests();

        return view('admin.blood-requests.index', compact('requests'));
    }

    public function create(): View
    {
        return view('admin.blood-requests.create');
    }

    public function store(StoreBloodRequestRequest $request): RedirectResponse
    {
        $this->bloodRequestService->createRequest(
            $request->validated(),
            $request->user()->id
        );

        return redirect()->route('admin.blood-requests.index')
            ->with('success', 'Blood request created successfully');
    }

    public function show(int $id): View
    {
        $bloodRequest = $this->bloodRequestService->getRequestById($id);

        if (!$bloodRequest) {
            abort(404);
        }

        return view('admin.blood-requests.show', compact('bloodRequest'));
    }

    public function update(UpdateBloodRequestRequest $request, int $id): RedirectResponse
    {
        $success = $this->bloodRequestService->updateRequest($id, $request->validated());

        if (!$success) {
            return back()->with('error', 'Failed to update blood request');
        }

        return redirect()->route('admin.blood-requests.show', $id)
            ->with('success', 'Blood request updated successfully');
    }

    public function updateStatus(UpdateBloodRequestStatusRequest $request, int $id): RedirectResponse
    {
        $payload = $request->validated();

        $this->bloodRequestService->updateRequestStatus(
            $id,
            $payload['status'],
            $request->user()->id
        );

        return redirect()->route('admin.blood-requests.show', $id)
            ->with('success', 'Blood request status updated successfully');
    }

    public function destroy(int $id): RedirectResponse
    {
        if (!$this->bloodRequestService->deleteRequest($id)) {
            return back()->with('error', 'Failed to delete blood request');
        }

        return redirect()->route('admin.blood-requests.index')
            ->with('success', 'Blood request deleted successfully');
    }
}
