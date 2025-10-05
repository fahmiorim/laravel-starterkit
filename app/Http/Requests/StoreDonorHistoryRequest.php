<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonorHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'donor_id' => ['required', 'exists:donors,id'],
            'blood_donation_schedule_id' => ['nullable', 'exists:blood_donation_schedules,id'],
            'tanggal_donor' => ['required', 'date'],
            'lokasi' => ['required', 'string', 'max:255'],
            'jumlah_kantong' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:pending,valid,ditolak'],
            'note' => ['nullable', 'string'],
        ];
    }
}
