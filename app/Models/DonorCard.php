<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonorCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'donor_id',
        'card_number',
        'qr_code_path',
        'card_photo_path',
        'issue_date',
        'expiry_date',
        'status',
        'notes'
    ];

    protected $dates = [
        'issue_date',
        'expiry_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'issue_date' => 'date:Y-m-d',
        'expiry_date' => 'date:Y-m-d',
    ];

    /**
     * Get the donor that owns the donor card.
     */
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    /**
     * Generate a unique card number.
     *
     * @return string
     */
    public static function generateCardNumber()
    {
        do {
            $number = 'KTA-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('card_number', $number)->exists());

        return $number;
    }

    /**
     * Check if the card is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 'active' && $this->expiry_date > now();
    }
}
