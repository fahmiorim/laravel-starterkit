<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BloodStockTransaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'blood_stock_id',
        'type',
        'quantity',
        'source_destination',
        'reference_number',
        'notes',
        'created_by',
        'approved_by',
        'approved_at',
        'status',
        'rejection_reason'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'quantity' => 'integer',
    ];

    // Relasi ke BloodStock
    public function bloodStock()
    {
        return $this->belongsTo(BloodStock::class);
    }

    // Relasi ke user yang membuat transaksi
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke user yang menyetujui transaksi
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scope untuk transaksi masuk
    public function scopeIncoming($query)
    {
        return $query->where('type', 'in');
    }

    // Scope untuk transaksi keluar
    public function scopeOutgoing($query)
    {
        return $query->where('type', 'out');
    }

    // Scope untuk transaksi yang menunggu persetujuan
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope untuk transaksi yang disetujui
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Scope untuk transaksi yang ditolak
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Cek apakah transaksi sudah disetujui
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    // Cek apakah transaksi sedang menunggu persetujuan
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    // Cek apakah transaksi ditolak
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
