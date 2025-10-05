<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Contracts\BloodRequestServiceInterface;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BloodRequestController extends Controller
{
    public function __construct(
        protected BloodRequestServiceInterface $bloodRequestService,
        protected NotificationService $notificationService
    ) {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        $bloodRequests = $this->bloodRequestService->getAllRequests();
        
        if (request()->wantsJson()) {
            return response()->json($bloodRequests);
        }
        
        return view('admin.blood-requests.index', compact('bloodRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.blood-requests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $bloodRequest = $this->bloodRequestService->createRequest(
            $request->all(),
            $request->user()->id
        );

        return response()->json([
            'message' => 'Blood request created successfully',
            'data' => $bloodRequest
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $bloodRequest = $this->bloodRequestService->getRequestById($id);
        
        if (!$bloodRequest) {
            abort(404);
        }

        return view('admin.blood-requests.show', compact('bloodRequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $success = $this->bloodRequestService->updateRequest($id, $request->all());

        if (!$success) {
            return response()->json([
                'message' => 'Failed to update blood request'
            ], 400);
        }

        return response()->json([
            'message' => 'Blood request updated successfully'
        ]);
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,rejected'
        ]);

        $bloodRequest = $this->bloodRequestService->updateRequestStatus(
            $id,
            $request->status,
            $request->user()->id
        );

        return response()->json([
            'message' => 'Blood request status updated successfully',
            'data' => $bloodRequest
        ]);
    }

    /**
     * Get blood requests by status
     */
    public function getByStatus(string $status): JsonResponse
    {
        $requests = $this->bloodRequestService->getRequestsByStatus($status);
        return response()->json($requests);
    }

    /**
     * Get recent blood requests
     */
    public function getRecent(): JsonResponse
    {
        $requests = $this->bloodRequestService->getRecentRequests(5);
        return response()->json($requests);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $success = $this->bloodRequestService->deleteRequest($id);

        if (!$success) {
            return response()->json([
                'message' => 'Failed to delete blood request'
            ], 400);
        }

        return response()->json([
            'message' => 'Blood request deleted successfully'
        ]);
    }
}
