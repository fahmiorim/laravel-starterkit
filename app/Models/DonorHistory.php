<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'blood_donation_schedule_id',
        'tanggal_donor',
        'lokasi',
        'jumlah_kantong',
        'status',
        'note',
    ];

    protected $casts = [
        'tanggal_donor' => 'date',
        'jumlah_kantong' => 'integer',
    ];

    protected static function booted()
    {
        static::saved(function (self $history) {
            $history->refreshDonorSnapshot();
        });

        static::deleted(function (self $history) {
            $history->refreshDonorSnapshot();
        });
    }

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function schedule()
    {
        return $this->belongsTo(DonationSchedule::class, 'blood_donation_schedule_id');
    }

    public function refreshDonorSnapshot(): void
    {
        if (! $this->donor_id) {
            return;
        }

        $donor = $this->donor()->first();

        if (! $donor) {
            return;
        }

        $validHistories = $donor->histories()->where('status', 'valid');

        $donor->last_donation_date = $validHistories->max('tanggal_donor');
        $donor->total_donations = $validHistories->count();
        $donor->saveQuietly();
    }
}
