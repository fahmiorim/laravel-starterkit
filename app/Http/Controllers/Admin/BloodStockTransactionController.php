<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBloodStockTransactionRequest;
use App\Http\Requests\UpdateBloodStockTransactionRequest;
use App\Models\BloodStockTransaction;
use App\Services\Contracts\BloodStockTransactionServiceInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class BloodStockTransactionController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly BloodStockTransactionServiceInterface $transactionService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', BloodStockTransaction::class);

        $transactions = $this->transactionService->paginate(
            $request->only('status')
        );

        return view('admin.blood-stock-transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', BloodStockTransaction::class);

        $formData = $this->transactionService->getFormData();

        return view('admin.blood-stock-transactions.create', $formData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBloodStockTransactionRequest $request)
    {
        $this->authorize('create', BloodStockTransaction::class);

        try {
            $transaction = $this->transactionService->create($request->validated(), $request->user());

            return redirect()->route('admin.blood-stock-transactions.index')
                ->with('success', 'Transaksi berhasil ditambahkan' . ($transaction->isApproved() ? ' dan stok telah diperbarui' : ''));
        } catch (\Throwable $e) {
            report($e);

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

        $formData = $this->transactionService->getFormData();

        return view('admin.blood-stock-transactions.edit', array_merge(
            ['bloodStockTransaction' => $bloodStockTransaction],
            $formData
        ));
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

        try {
            $transaction = $this->transactionService->update($bloodStockTransaction, $request->validated());

            return redirect()->route('admin.blood-stock-transactions.show', $transaction)
                ->with('success', 'Transaksi berhasil diperbarui' . ($transaction->isApproved() ? ' dan stok telah diperbarui' : ''));
        } catch (\Throwable $e) {
            report($e);

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

        try {
            $this->transactionService->delete($bloodStockTransaction);

            return redirect()->route('admin.blood-stock-transactions.index')
                ->with('success', 'Transaksi berhasil dihapus');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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

        try {
            $this->transactionService->approve($bloodStockTransaction, $request->user());

            // TODO: Kirim notifikasi ke pembuat transaksi

            return back()->with('success', 'Transaksi berhasil disetujui dan stok telah diperbarui');
        } catch (\Throwable $e) {
            report($e);

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

        try {
            $this->transactionService->reject(
                $bloodStockTransaction,
                $request->user(),
                $request->rejection_reason
            );

            // TODO: Kirim notifikasi ke pembuat transaksi

            return back()->with('success', 'Transaksi berhasil ditolak');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
