<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBloodRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['sometimes', 'required', 'exists:patients,id'],
            'blood_type_id' => ['sometimes', 'required', 'exists:blood_types,id'],
            'quantity' => ['sometimes', 'required', 'integer', 'min:1'],
            'status' => ['sometimes', 'required', 'in:pending,processing,completed,rejected'],
            'notes' => ['nullable', 'string'],
            'hospital_name' => ['nullable', 'string', 'max:255'],
            'hospital_address' => ['nullable', 'string'],
            'required_date' => ['nullable', 'date'],
        ];
    }
}
