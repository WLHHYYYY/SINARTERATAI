<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('created_at', 'desc')->get();
        return view('supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:100',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:50'
        ]);

        Supplier::create($validated);

        return redirect()
            ->route('supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan');
    }

    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:100',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:50'
        ]);

        $supplier->update($validated);

        return redirect()
            ->route('supplier.index')
            ->with('success', 'Supplier berhasil diperbarui');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->stok()->exists()) {
            return redirect()
                ->route('supplier.index')
                ->with('error', 'Supplier tidak dapat dihapus karena masih memiliki stok terkait');
        }

        $supplier->delete();

        return redirect()
            ->route('supplier.index')
            ->with('success', 'Supplier berhasil dihapus');
    }
}
