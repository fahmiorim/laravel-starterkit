<?php

namespace App\DTOs;

use Carbon\Carbon;
use Illuminate\Http\Request;

class BloodRequestData
{
    public function __construct(
        public readonly int $patient_id,
        public readonly int $blood_type_id,
        public readonly int $quantity,
        public readonly string $status,
        public readonly ?string $notes = null,
        public readonly ?string $hospital_name = null,
        public readonly ?string $hospital_address = null,
        public readonly ?Carbon $required_date = null,
        public readonly ?int $processed_by = null
    ) {}

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'blood_type_id' => 'required|exists:blood_types,id',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:pending,processing,completed,rejected',
            'notes' => 'nullable|string',
            'hospital_name' => 'nullable|string|max:255',
            'hospital_address' => 'nullable|string',
            'required_date' => 'nullable|date',
        ]);

        return new self(
            patient_id: (int)$validated['patient_id'],
            blood_type_id: (int)$validated['blood_type_id'],
            quantity: (int)$validated['quantity'],
            status: $validated['status'],
            notes: $validated['notes'] ?? null,
            hospital_name: $validated['hospital_name'] ?? null,
            hospital_address: $validated['hospital_address'] ?? null,
            required_date: isset($validated['required_date']) 
                ? Carbon::parse($validated['required_date'])
                : null,
            processed_by: $request->user()?->id
        );
    }

    /**
     * Convert DTO to array
     */
    public function toArray(): array
    {
        return [
            'patient_id' => $this->patient_id,
            'blood_type_id' => $this->blood_type_id,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'notes' => $this->notes,
            'hospital_name' => $this->hospital_name,
            'hospital_address' => $this->hospital_address,
            'required_date' => $this->required_date,
            'processed_by' => $this->processed_by,
        ];
    }
}
