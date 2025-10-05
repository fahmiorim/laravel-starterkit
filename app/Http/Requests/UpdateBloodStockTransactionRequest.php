<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBloodStockTransactionRequest extends FormRequest
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
    public function rules(): array
    {
        $transaction = $this->route('blood_stock_transaction');
        $isPending = !$transaction || $transaction->isPending();
        
        $rules = [
            'blood_stock_id' => ['sometimes', 'required', 'exists:blood_stocks,id'],
            'type' => ['sometimes', 'required', 'in:in,out'],
            'quantity' => [
                'sometimes',
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($transaction, $isPending) {
                    if (!$isPending) {
                        $fail('Tidak dapat mengubah jumlah transaksi yang sudah disetujui/ditolak');
                        return;
                    }
                    
                    $type = $this->input('type', $transaction ? $transaction->type : null);
                    $bloodStockId = $this->input('blood_stock_id', $transaction ? $transaction->blood_stock_id : null);
                    
                    if ($type === 'out' && $bloodStockId) {
                        $bloodStock = \App\Models\BloodStock::find($bloodStockId);
                        $currentQuantity = $transaction ? $transaction->quantity : 0;
                        $available = $bloodStock->quantity + $currentQuantity; // Kembalikan dulu stok yang akan diupdate
                        
                        if ($value > $available) {
                            $fail("Stok tidak mencukupi. Stok tersedia: {$available} kantong");
                        }
                    }
                }
            ],
            'source_destination' => ['sometimes', 'required', 'string', 'max:255'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:pending,approved,rejected'],
        ];

        return $rules;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $transaction = $this->route('blood_stock_transaction');
            
            // Validasi tambahan untuk transaksi yang sudah disetujui/ditolak
            if ($transaction && !$transaction->isPending()) {
                $changed = array_intersect(
                    array_keys($this->input()),
                    ['blood_stock_id', 'type', 'quantity', 'source_destination']
                );
                
                if (!empty($changed)) {
                    $validator->errors()->add('status', 'Tidak dapat mengubah transaksi yang sudah disetujui/ditolak');
                }
            }
        });
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
            'source_destination.required' => 'Sumber/Tujuan harus diisi',
            'source_destination.max' => 'Sumber/Tujuan maksimal 255 karakter',
            'reference_number.max' => 'Nomor referensi maksimal 100 karakter',
            'status.in' => 'Status tidak valid',
        ];
    }
}
