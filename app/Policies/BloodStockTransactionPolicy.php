<?php

namespace App\Policies;

use App\Models\BloodStockTransaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BloodStockTransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'staff']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BloodStockTransaction $bloodStockTransaction): bool
    {
        // Admin dan staff bisa melihat semua transaksi
        // Pengguna biasa hanya bisa melihat transaksi yang mereka buat
        return $user->hasAnyRole(['admin', 'staff']) || 
               $bloodStockTransaction->created_by === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Hanya admin dan staff yang bisa membuat transaksi
        return $user->hasAnyRole(['admin', 'staff']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BloodStockTransaction $bloodStockTransaction): bool
    {
        // Hanya admin yang bisa mengupdate transaksi
        // Dan hanya transaksi yang belum disetujui/ditolak yang bisa diupdate
        return $user->hasRole('admin') && 
               $bloodStockTransaction->isPending();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BloodStockTransaction $bloodStockTransaction): bool
    {
        // Hanya admin yang bisa menghapus transaksi
        // Dan hanya transaksi yang belum disetujui/ditolak yang bisa dihapus
        return $user->hasRole('admin') && 
               $bloodStockTransaction->isPending();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BloodStockTransaction $bloodStockTransaction): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BloodStockTransaction $bloodStockTransaction): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can approve the transaction.
     */
    public function approve(User $user, BloodStockTransaction $bloodStockTransaction): bool
    {
        // Hanya admin yang bisa menyetujui transaksi
        // Dan hanya transaksi yang masih pending yang bisa disetujui
        return $user->hasRole('admin') && 
               $bloodStockTransaction->isPending();
    }
}
