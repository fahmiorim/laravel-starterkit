<?php

namespace App\Rules;

use App\Models\BloodStock;
use Illuminate\Contracts\Validation\Rule;

class SufficientStock implements Rule
{
    protected $bloodStockId;
    protected $currentTransactionId;
    protected $availableStock;

    /**
     * Create a new rule instance.
     *
     * @param int $bloodStockId
     * @param int|null $currentTransactionId
     * @return void
     */
    public function __construct($bloodStockId, $currentTransactionId = null)
    {
        $this->bloodStockId = $bloodStockId;
        $this->currentTransactionId = $currentTransactionId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $bloodStock = BloodStock::find($this->bloodStockId);
        
        if (!$bloodStock) {
            return false;
        }
        
        $this->availableStock = $bloodStock->quantity;
        
        // Jika ini adalah update, tambahkan kembali stok dari transaksi yang sedang diupdate
        if ($this->currentTransactionId) {
            $currentTransaction = \App\Models\BloodStockTransaction::find($this->currentTransactionId);
            if ($currentTransaction && $currentTransaction->blood_stock_id == $this->bloodStockId) {
                $this->availableStock += $currentTransaction->quantity;
            }
        }
        
        return $value <= $this->availableStock;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Stok tidak mencukupi. Stok tersedia: ' . $this->availableStock . ' kantong';
    }
}
