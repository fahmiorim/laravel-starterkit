<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBloodStockTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Akan dihandle oleh policy
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'blood_stock_id' => ['required', 'exists:blood_stocks,id'],
            'type' => ['required', 'in:in,out'],
            'quantity' => [
                'required', 
                'integer', 
                'min:1',
            ],
            'source_destination' => ['required', 'string', 'max:255'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ];

        // Add stock validation for outgoing transactions
        if ($this->input('type') === 'out' && $this->input('blood_stock_id')) {
            $rules['quantity'][] = new \App\Rules\SufficientStock($this->input('blood_stock_id'));
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'blood_stock_id.required' => 'Golongan darah harus dipilih',
            'blood_stock_id.exists' => 'Golongan darah tidak valid',
            'type.required' => 'Jenis transaksi harus dipilih',
            'type.in' => 'Jenis transaksi tidak valid',
            'quantity.required' => 'Jumlah harus diisi',
            'quantity.integer' => 'Jumlah harus berupa angka',
            'quantity.min' => 'Jumlah minimal 1 kantong',
            'quantity.max_available_stock' => 'Stok tidak mencukupi. Stok tersedia: :max kantong',
            'source_destination.required' => 'Sumber/Tujuan harus diisi',
            'source_destination.max' => 'Sumber/Tujuan maksimal 255 karakter',
            'reference_number.max' => 'Nomor referensi maksimal 100 karakter',
        ];
    }
}
