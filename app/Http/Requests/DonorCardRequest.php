<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DonorCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'donor_id' => [
                'required',
                'exists:donors,id',
                Rule::unique('donor_cards', 'donor_id')->ignore($this->route('donor_card'))
            ],
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'notes' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        if ($this->isMethod('post')) {
            $rules['photo'][] = 'required';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'donor_id.required' => 'Pendonor harus dipilih',
            'donor_id.exists' => 'Pendonor tidak valid',
            'donor_id.unique' => 'Pendonor ini sudah memiliki kartu donor',
            'issue_date.required' => 'Tanggal terbit harus diisi',
            'expiry_date.required' => 'Tanggal kadaluarsa harus diisi',
            'expiry_date.after' => 'Tanggal kadaluarsa harus setelah tanggal terbit',
            'photo.required' => 'Foto wajib diunggah',
            'photo.image' => 'File harus berupa gambar',
            'photo.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'photo.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }
}
