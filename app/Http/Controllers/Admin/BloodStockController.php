<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodStock;
use Illuminate\Http\Request;

class BloodStockController extends Controller
{
    public function index()
    {
        $stocks = BloodStock::orderBy('blood_type')->orderBy('rhesus')->get();

        return view('admin.blood-stocks.index', compact('stocks'));
    }

    public function update(Request $request, BloodStock $bloodStock)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $bloodStock->update([
            'quantity' => $validated['quantity'],
            'updated_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.blood-stocks.index')->with('success', 'Stok darah berhasil diperbarui.');
    }
}
