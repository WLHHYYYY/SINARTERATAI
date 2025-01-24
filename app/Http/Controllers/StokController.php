<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $query = Stok::where('status', 'approved')
            ->with(['barang', 'supplier', 'diajukanOleh', 'disetujuiOleh']);

        if ($request->filled('search')) {
            $query->whereHas('barang', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $stoks = $query->orderBy('created_at', 'desc')->get();


        return view('stok.index', compact('stoks'));
    }

    public function create()
    {
        // Ambil data barang dan supplier untuk dropdown
        $barangs = Barang::all(['id', 'nama_barang']);
        $suppliers = Supplier::all(['id', 'nama_supplier']);
        $role = auth()->user()->role;


        return view('stok.create', compact('barangs', 'suppliers', 'role'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_expired' => 'required|date|after:today',
        ]);

        $isSuper = auth()->user()->role == 'supervisor' || auth()->user()->role == 'direktur';

        $stok = Stok::create([
            'barang_id' => $validated['barang_id'],
            'supplier_id' => $validated['supplier_id'],
            'jumlah' => $validated['jumlah'],
            'tanggal_expired' => $validated['tanggal_expired'],
            'status' => $isSuper ? 'approved' : 'pending',
            'diajukan_oleh' => auth()->id(),
            'disetujui_oleh' => $isSuper ? auth()->id() : null,
        ]);

        // Log activity for creating a new stock
        $this->createLogActivity('Mengajukan stok baru untuk barang ' . $stok->barang->nama_barang);

        if ($isSuper) {
            // Trigger pengisian tabel RiwayatMasuk
            \App\Models\RiwayatMasuk::create([
                'stok_id' => $stok->id,
                'jumlah_masuk' => $stok->jumlah,
                'tanggal_masuk' => now(),
                'diajukan_oleh' => $stok->diajukan_oleh,
                'disetujui_oleh' => $stok->disetujui_oleh,
            ]);
        }

        return redirect()
            ->route('stok.index')
            ->with('success', 'Stock berhasil ditambahkan.');
    }

    public function riwayat(Request $request)
    {
        $query = Stok::query()
            ->with(['barang', 'supplier', 'diajukanOleh', 'disetujuiOleh'])
            ->select('stoks.*')
            ->leftJoin('riwayat_masuks', 'stoks.id', '=', 'riwayat_masuks.stok_id')
            ->selectRaw('CASE
                WHEN stoks.status = "approved" THEN COALESCE(riwayat_masuks.jumlah_masuk, stoks.jumlah)
                ELSE stoks.jumlah
                END as jumlah_display');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date . ' 00:00:00';
            $endDate = $request->end_date . ' 23:59:59';
            $query->whereBetween('stoks.created_at', [$startDate, $endDate]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('stoks.created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('stoks.created_at', '<=', $request->end_date . ' 23:59:59');
        }


        $stoks = $query->orderBy('stoks.created_at', 'desc')->paginate(15);

        return view('stok.riwayat', compact('stoks'));
    }


    private function createLogActivity($aktivitas)
    {
        \App\Models\LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => $aktivitas,
        ]);
    }
}
