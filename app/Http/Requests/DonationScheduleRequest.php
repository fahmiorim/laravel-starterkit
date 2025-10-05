<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationScheduleRequest extends FormRequest
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
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'required|string|max:500',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'kuota' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,canceled',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // For update, make image optional
        if ($this->isMethod('patch') || $this->isMethod('put')) {
            $rules['gambar'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
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
            'judul.required' => 'Judul jadwal harus diisi',
            'lokasi.required' => 'Lokasi harus diisi',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai',
            'kuota.min' => 'Kuota minimal 1 orang',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar yang didukung: jpeg, png, jpg, gif',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }
}
