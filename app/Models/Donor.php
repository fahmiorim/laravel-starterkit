<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nik',
        'kta_number',
        'gender',
        'birth_date',
        'address',
        'phone',
        'blood_type',
        'rhesus',
        'last_donation_date',
        'total_donations',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'last_donation_date' => 'date',
        'kta_issued_at' => 'datetime',
        'total_donations' => 'integer',
    ];

    public function histories()
    {
        return $this->hasMany(DonorHistory::class);
    }

    /**
     * Get the donor card associated with the donor.
     */
    public function donorCard()
    {
        return $this->hasOne(DonorCard::class);
    }

    /**
     * Check if donor has an active card.
     *
     * @return bool
     */
    public function hasActiveCard()
    {
        return $this->donorCard && $this->donorCard->isActive();
    }
}
