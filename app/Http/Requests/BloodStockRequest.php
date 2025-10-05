<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BloodStockRequest extends FormRequest
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
        $bloodTypes = ['A', 'B', 'AB', 'O'];
        $rhesusTypes = ['+', '-'];
        
        return [
            'blood_type' => ['required', 'string', Rule::in($bloodTypes)],
            'rhesus' => ['required', 'string', Rule::in($rhesusTypes)],
            'quantity' => ['required', 'integer', 'min:0'],
            'donation_date' => ['required', 'date'],
            'expiry_date' => ['required', 'date', 'after:donation_date'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'blood_type.required' => 'Golongan darah wajib diisi.',
            'blood_type.in' => 'Golongan darah tidak valid.',
            'rhesus.required' => 'Rhesus wajib diisi.',
            'rhesus.in' => 'Rhesus tidak valid.',
            'quantity.required' => 'Jumlah stok wajib diisi.',
            'quantity.integer' => 'Jumlah stok harus berupa angka.',
            'quantity.min' => 'Jumlah stok minimal :min.',
            'donation_date.required' => 'Tanggal donasi wajib diisi.',
            'donation_date.date' => 'Format tanggal donasi tidak valid.',
            'expiry_date.required' => 'Tanggal kadaluarsa wajib diisi.',
            'expiry_date.date' => 'Format tanggal kadaluarsa tidak valid.',
            'expiry_date.after' => 'Tanggal kadaluarsa harus setelah tanggal donasi.',
            'low_stock_threshold.integer' => 'Batas stok minimum harus berupa angka.',
            'low_stock_threshold.min' => 'Batas stok minimum minimal :min.',
            'notes.max' => 'Catatan maksimal :max karakter.',
        ];
    }
}
