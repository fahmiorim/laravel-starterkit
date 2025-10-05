<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DonationSchedule extends Model
{
    use HasFactory;
    protected $table = 'blood_donation_schedules';

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'lokasi',
        'tanggal_mulai',
        'tanggal_selesai',
        'penanggung_jawab',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($schedule) {
            $schedule->slug = Str::slug($schedule->judul);
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function histories()
    {
        return $this->hasMany(DonorHistory::class, 'blood_donation_schedule_id');
    }
}
