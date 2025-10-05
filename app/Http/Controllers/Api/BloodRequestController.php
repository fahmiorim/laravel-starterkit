<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBloodRequestRequest;
use App\Http\Requests\UpdateBloodRequestRequest;
use App\Http\Requests\UpdateBloodRequestStatusRequest;
use App\Services\Contracts\BloodRequestServiceInterface;
use Illuminate\Http\JsonResponse;

class BloodRequestController extends Controller
{
    public function __construct(
        protected BloodRequestServiceInterface $bloodRequestService
    ) {}

    public function index(): JsonResponse
    {
        $requests = $this->bloodRequestService->getAllRequests();

        return response()->json($requests);
    }

    public function store(StoreBloodRequestRequest $request): JsonResponse
    {
        $bloodRequest = $this->bloodRequestService->createRequest(
            $request->validated(),
            $request->user()->id
        );

        return response()->json([
            'message' => 'Blood request created successfully',
            'data' => $bloodRequest
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $bloodRequest = $this->bloodRequestService->getRequestById($id);

        if (!$bloodRequest) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($bloodRequest);
    }

    public function update(UpdateBloodRequestRequest $request, int $id): JsonResponse
    {
        if (!$this->bloodRequestService->updateRequest($id, $request->validated())) {
            return response()->json(['message' => 'Failed to update blood request'], 400);
        }

        return response()->json(['message' => 'Blood request updated successfully']);
    }

    public function updateStatus(UpdateBloodRequestStatusRequest $request, int $id): JsonResponse
    {
        $payload = $request->validated();

        $bloodRequest = $this->bloodRequestService->updateRequestStatus(
            $id,
            $payload['status'],
            $request->user()->id
        );

        return response()->json([
            'message' => 'Blood request status updated successfully',
            'data' => $bloodRequest
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        if (!$this->bloodRequestService->deleteRequest($id)) {
            return response()->json(['message' => 'Failed to delete blood request'], 400);
        }

        return response()->json(['message' => 'Blood request deleted successfully']);
    }

    public function getByStatus(string $status): JsonResponse
    {
        $requests = $this->bloodRequestService->getRequestsByStatus($status);

        return response()->json($requests);
    }

    public function getRecent(): JsonResponse
    {
        $requests = $this->bloodRequestService->getRecentRequests(5);

        return response()->json($requests);
    }
}
