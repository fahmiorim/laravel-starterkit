<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_name',
        'patient_name',
        'blood_type',
        'rhesus',
        'quantity',
        'status',
        'notes',
        'processed_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
