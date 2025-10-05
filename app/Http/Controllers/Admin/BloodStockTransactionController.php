<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBloodStockTransactionRequest;
use App\Http\Requests\UpdateBloodStockTransactionRequest;
use App\Models\BloodStock;
use App\Models\BloodStockTransaction;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BloodStockTransactionController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', BloodStockTransaction::class);

        $transactions = BloodStockTransaction::with(['bloodStock', 'creator', 'approver'])
            ->latest()
            ->paginate(15);

        return view('admin.blood-stock-transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', BloodStockTransaction::class);

        $bloodStocks = BloodStock::all();
        $users = User::all();
        
        return view('admin.blood-stock-transactions.create', compact('bloodStocks', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBloodStockTransactionRequest $request)
    {
        $this->authorize('create', BloodStockTransaction::class);

        $validated = $request->validated();
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'pending';

        // If the creator is an admin, automatically approve
        if ($request->user()->hasRole('admin')) {
            $validated['approved_by'] = Auth::id();
            $validated['approved_at'] = now();
            $validated['status'] = 'approved';
        }

        DB::beginTransaction();
        
        try {
            $transaction = BloodStockTransaction::create($validated);
            
            // Update stok jika transaksi disetujui
            if ($transaction->isApproved()) {
                $this->updateStock($transaction);
            }
            
            DB::commit();
            
            return redirect()->route('admin.blood-stock-transactions.index')
                ->with('success', 'Transaksi berhasil ditambahkan' . ($transaction->isApproved() ? ' dan stok telah diperbarui' : ''));
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BloodStockTransaction $bloodStockTransaction)
    {
        $this->authorize('view', $bloodStockTransaction);
        
        $bloodStockTransaction->load(['bloodStock', 'creator', 'approver']);
        
        return view('admin.blood-stock-transactions.show', compact('bloodStockTransaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BloodStockTransaction $bloodStockTransaction)
    {
        $this->authorize('update', $bloodStockTransaction);
        
        if ($bloodStockTransaction->isApproved()) {
            return redirect()->route('admin.blood-stock-transactions.show', $bloodStockTransaction)
                ->with('error', 'Transaksi yang sudah disetujui tidak dapat diedit');
        }
        
        $bloodStocks = BloodStock::all();
        $users = User::all();
        
        return view('admin.blood-stock-transactions.edit', compact('bloodStockTransaction', 'bloodStocks', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBloodStockTransactionRequest $request, BloodStockTransaction $bloodStockTransaction)
    {
        $this->authorize('update', $bloodStockTransaction);
        
        if ($bloodStockTransaction->isApproved()) {
            return back()->with('error', 'Transaksi yang sudah disetujui tidak dapat diubah');
        }
        
        $validated = $request->validated();
        
        DB::beginTransaction();
        
        try {
            $bloodStockTransaction->update($validated);
            
            // Jika transaksi disetujui, update stok
            if ($bloodStockTransaction->isApproved()) {
                $this->updateStock($bloodStockTransaction);
            }
            
            DB::commit();
            
            return redirect()->route('admin.blood-stock-transactions.show', $bloodStockTransaction)
                ->with('success', 'Transaksi berhasil diperbarui' . ($bloodStockTransaction->isApproved() ? ' dan stok telah diperbarui' : ''));
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloodStockTransaction $bloodStockTransaction)
    {
        $this->authorize('delete', $bloodStockTransaction);
        
        if ($bloodStockTransaction->isApproved()) {
            return back()->with('error', 'Transaksi yang sudah disetujui tidak dapat dihapus');
        }
        
        $bloodStockTransaction->delete();
        
        return redirect()->route('admin.blood-stock-transactions.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }
    
    /**
     * Approve the specified transaction.
     */
    public function approve(Request $request, BloodStockTransaction $bloodStockTransaction)
    {
        $this->authorize('approve', $bloodStockTransaction);
        
        if ($bloodStockTransaction->isApproved()) {
            return back()->with('error', 'Transaksi sudah disetujui sebelumnya');
        }
        
        if ($bloodStockTransaction->isRejected()) {
            return back()->with('error', 'Transaksi yang sudah ditolak tidak dapat disetujui');
        }
        
        DB::beginTransaction();
        
        try {
            $bloodStockTransaction->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'rejection_reason' => null,
            ]);
            
            // Update stok
            $this->updateStock($bloodStockTransaction);
            
            DB::commit();
            
            // TODO: Kirim notifikasi ke pembuat transaksi
            
            return back()->with('success', 'Transaksi berhasil disetujui dan stok telah diperbarui');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Reject the specified transaction.
     */
    public function reject(Request $request, BloodStockTransaction $bloodStockTransaction)
    {
        $this->authorize('approve', $bloodStockTransaction);
        
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);
        
        if ($bloodStockTransaction->isApproved()) {
            return back()->with('error', 'Transaksi yang sudah disetujui tidak dapat ditolak');
        }
        
        if ($bloodStockTransaction->isRejected()) {
            return back()->with('error', 'Transaksi sudah ditolak sebelumnya');
        }
        
        $bloodStockTransaction->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);
        
        // TODO: Kirim notifikasi ke pembuat transaksi
        
        return back()->with('success', 'Transaksi berhasil ditolak');
    }
    
    /**
     * Update stock based on transaction.
     */
    protected function updateStock(BloodStockTransaction $transaction)
    {
        $bloodStock = $transaction->bloodStock;
        
        if ($transaction->type === 'in') {
            $bloodStock->increment('quantity', $transaction->quantity);
        } else {
            $bloodStock->decrement('quantity', $transaction->quantity);
        }
    }
}
