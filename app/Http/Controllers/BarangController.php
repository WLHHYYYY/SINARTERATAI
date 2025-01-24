<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\JenisObat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with(['jenisObat', 'createdBy'])->orderBy('created_at', 'desc')->get();
        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        $jenisObat = JenisObat::all();
        return view('barang.create', compact('jenisObat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:100',
            'jenis_id' => 'required|exists:jenis_obats,id',
            'deskripsi' => 'nullable|string'
        ]);

        $barang = new Barang($validated);
        $barang->created_by = Auth::id();
        $barang->save();

        $this->createLogActivity("Menambahkan barang baru: {$barang->nama_barang}");

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Barang $barang)
    {
        $jenisObat = JenisObat::all();
        return view('barang.edit', compact('barang', 'jenisObat'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:100',
            'jenis_id' => 'required|exists:jenis_obats,id',
            'deskripsi' => 'nullable|string'
        ]);

        $oldNamaBarang = $barang->nama_barang;
        $barang->update($validated);

        // Log activity for updating barang
        $this->createLogActivity("Memperbarui barang: dari {$oldNamaBarang} menjadi {$barang->nama_barang}");

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->stok()->exists()) {
            return redirect()
                ->route('barang.index')
                ->with('error', 'Barang tidak dapat dihapus karena masih memiliki stok terkait');
        }

        $namaBarang = $barang->nama_barang;
        $barang->delete();

        $this->createLogActivity("Menghapus barang: {$namaBarang}");

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil dihapus');
    }

    /**
     * Create a log activity entry
     *
     * @param string $aktivitas
     */
    private function createLogActivity($aktivitas)
    {
        \App\Models\LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => $aktivitas,
        ]);
    }
}
