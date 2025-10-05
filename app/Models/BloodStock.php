<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_type',
        'rhesus',
        'quantity',
        'updated_by',
        'donation_date',
        'expiry_date',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'donation_date' => 'datetime',
        'expiry_date' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($bloodStock) {
            if (empty($bloodStock->donation_date)) {
                $bloodStock->donation_date = now();
            }
            if (empty($bloodStock->expiry_date)) {
                $bloodStock->expiry_date = now()->addDays(35); // Masa simpan darah 35 hari
            }
        });
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getLabelAttribute(): string
    {
        return $this->blood_type.' '.$this->rhesus;
    }

    public function isAboutToExpire(): bool
    {
        return $this->expiry_date && $this->expiry_date->diffInDays(now()) <= 7;
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
