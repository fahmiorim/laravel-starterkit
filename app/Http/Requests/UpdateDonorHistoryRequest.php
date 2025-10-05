<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDonorHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'donor_id' => ['sometimes', 'required', 'exists:donors,id'],
            'blood_donation_schedule_id' => ['nullable', 'exists:blood_donation_schedules,id'],
            'tanggal_donor' => ['sometimes', 'required', 'date'],
            'lokasi' => ['sometimes', 'required', 'string', 'max:255'],
            'jumlah_kantong' => ['sometimes', 'required', 'integer', 'min:1'],
            'status' => ['sometimes', 'required', 'in:pending,valid,ditolak'],
            'note' => ['nullable', 'string'],
        ];
    }
}
