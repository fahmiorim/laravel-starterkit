<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BloodStockRequest;
use App\Models\BloodStock;
use App\Services\BloodStockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BloodStockController extends Controller
{
    protected $bloodStockService;

    public function __construct(BloodStockService $bloodStockService)
    {
        $this->bloodStockService = $bloodStockService;
    }

    /**
     * Menampilkan daftar stok darah
     */
    public function index()
    {
        $stocks = $this->bloodStockService->getAllStocks();
        $lowStocks = $this->bloodStockService->getLowStocks();
        $expiringSoon = $this->bloodStockService->getExpiringStocks();

        return view('admin.blood-stocks.index', compact('stocks', 'lowStocks', 'expiringSoon'));
    }

    /**
     * Menampilkan form tambah stok darah
     */
    public function create()
    {
        return view('admin.blood-stocks.create');
    }

    /**
     * Menyimpan stok darah baru
     */
    public function store(BloodStockRequest $request)
    {
        try {
            $data = $request->validated();
            $this->bloodStockService->createStock($data);
            
            return redirect()
                ->route('admin.blood-stocks.index')
                ->with('success', 'Stok darah berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating blood stock: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menambahkan stok darah.');
        }
    }

    /**
     * Menampilkan detail stok darah
     */
    public function show(BloodStock $bloodStock)
    {
        $stock = $this->bloodStockService->getStockById($bloodStock->id);
        return view('admin.blood-stocks.show', compact('stock'));
    }

    /**
     * Menampilkan form edit stok darah
     */
    public function edit(BloodStock $bloodStock)
    {
        $stock = $this->bloodStockService->getStockById($bloodStock->id);
        return view('admin.blood-stocks.edit', compact('stock'));
    }

    /**
     * Memperbarui stok darah
     */
    public function update(BloodStockRequest $request, BloodStock $bloodStock)
    {
        try {
            $data = $request->validated();
            $this->bloodStockService->updateStock($bloodStock, $data);
            
            return redirect()
                ->route('admin.blood-stocks.index')
                ->with('success', 'Stok darah berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating blood stock: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui stok darah.');
        }
    }

    /**
     * Menghapus stok darah
     */
    public function destroy(BloodStock $bloodStock)
    {
        try {
            $this->bloodStockService->deleteStock($bloodStock);
            return redirect()
                ->route('admin.blood-stocks.index')
                ->with('success', 'Stok darah berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting blood stock: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus stok darah.');
        }
    }
}
