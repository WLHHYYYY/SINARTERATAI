<?php

namespace App\Http\Controllers;

use App\Models\StockAdjustment;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;

class S_tockAdjustmentController extends Controller
{

    public function index()
    {
        $adjustments = StockAdjustment::with('item', 'user')
            ->orderBy('updated_at', 'desc') // Urutkan berdasarkan tanggal terbaru
            ->take(10) // Ambil hanya 10 data terbaru
            ->get();

        return view('stock_adjustments.index', compact('adjustments'));

        $adjustments = StockAdjustment::with('item', 'user')->paginate(10);
        return view('stock_adjustments.index', compact('adjustments'));
    }

    public function create()
    {
        $items = Item::all(); // Ambil semua item untuk pilihan
        return view('stock_adjustments.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:increase,decrease',
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($request->type == 'decrease' && $item->stock < $request->quantity) {
            return redirect()->back()->withErrors(['quantity' => 'Stok tidak cukup untuk pengurangan.']);
        }

        // Catat ke dalam stock_adjustments
        StockAdjustment::create([
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'type' => $request->type,
            'status' => 'approved', // Bisa diubah jika ingin status 'pending' dulu
        ]);

        // Update stok barang
        if ($request->type == 'increase') {
            $item->increment('stock', $request->quantity);
        } else {
            $item->decrement('stock', $request->quantity);
        }

        return redirect()->route('items.index')->with('success', 'Stok berhasil diperbarui.');
    }

    //Kurangi Barang
    public function reduce(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($item->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi.');
        }

        $item->stock -= $request->quantity;
        $item->save();

        StockAdjustment::create([
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'type' => 'decrease',
            'status' => 'approved',
        ]);

        return redirect()->route('items.index')->with('success', 'Stok berhasil dikurangi.');
    }

    //Request Penambahan Stok Barang
    //Jadi Fungsi ini tu dipanggil pas button 'Tambah Stok' di klik
    public function increase(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = Item::find($request->item_id);

        // Tambahkan ke reserved_stock jika bukan supervisor
        if (auth()->user()->role !== 'supervisor') {
            $item->reserved_stock += $request->quantity;
            $item->save();

            StockAdjustment::create([
                'item_id' => $request->item_id,
                'quantity' => $request->quantity,
                'type' => 'increase',
                'status' => 'pending', // Status awal pending
            ]);

            return redirect()->route('stock_adjustments.increase')->with('success', 'Stok telah ditambahkan ke stok cadangan. Menunggu persetujuan supervisor.');
        }

        StockAdjustment::create([
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'type' => 'increase',
            'status' => 'approved',
        ]);

        $item->stock += $request->quantity;
        $item->save();

        return redirect()->route('stock_adjustments.increase')->with('success', 'Stok berhasil ditambahkan.');
    }

    //Fungsi ini tu dipanggil saat supervisor nyetujui barang
    //parameter ny yo id dari barang nyo
    public function approveStock($id)
    {
        $riwayatStock = StockAdjustment::findOrFail($id);

        if ($riwayatStock->status === 'pending') {
            // Tambahkan stok ke tabel items
            $item = Item::findOrFail($riwayatStock->item_id);
            $item->stock += $riwayatStock->quantity;
            $item->reserved_stock -= $riwayatStock->quantity;
            $item->save();

            // Ubah status di tabel riwayat_stock
            $riwayatStock->status = 'approved';
            $riwayatStock->save();
        }

        return redirect()->back()->with('success', 'Stok barang berhasil disetujui.');
    }

    //reject stock
    public function rejectStock($id)
    {
        $riwayatStock = StockAdjustment::findOrFail($id);
        $item = Item::findOrFail($riwayatStock->item_id);
        // Pastikan status masih pending
        if ($riwayatStock->status === 'pending') {
            $riwayatStock->status = 'rejected';
            $item->reserved_stock -= $riwayatStock->quantity;
            $riwayatStock->save();
            $item->save();
        }

        return redirect()->back()->with('success', 'Pengajuan stok berhasil ditolak.');
    }

    //barang yang di pending
    public function pendingApprovals()
    {
        // Ambil barang dengan reserved_stock > 0
        $pendingItems = Item::where('reserved_stock', '>', 0)->get();
        return view('stock_adjustments.pending', compact('pendingItems'));
    }

    public function pendingStock()
    {
        $riwayatStock = StockAdjustment::where('status', 'pending')
            ->where('type', 'increase')
            ->with('item')
            ->get();

        if ($riwayatStock->isEmpty()) {
            return view('stock_adjustments.pending')->with('message', 'Semua stok telah disetujui.');
        }

        return view('stock_adjustments.pending', ['riwayatStock' => $riwayatStock]);
    }



    public function pending()
    {
        $pendingAdjustments = StockAdjustment::where('status', 'pending')->with('item')->get();
        return view('stock_adjustments.pending', compact('pendingAdjustments'));
    }

    public function approved()
    {
        $approvedAdjustments = StockAdjustment::where('status', 'approved')->with('item')->get();
        return view('items.sold', compact('approvedAdjustments'));
    }

    //Fungsi ini tu cuma buat view jugo, tapi ditambai kondisi. Jadi pas klik ni MENU 'Barang Terjual',
    //Funsi ini yang di panggel.
    public function soldItems(Request $request)
    {
        // Menangkap input filter tanggal dari pengguna
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Query untuk mendapatkan barang yang terjual dan disetujui oleh supervisor
        $query = StockAdjustment::where('type', 'decrease')
            ->where('status', 'approved')
            ->with('item')
            ->orderBy('updated_at', 'desc');

        // Menambahkan filter berdasarkan tanggal jika ada
        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        }

        // Mengambil data yang telah difilter
        $approvedAdjustments = $query->get();

        // Mengirim data ke view
        return view('items.sold', compact('approvedAdjustments'));
    }


    public function incomingItems(Request $request)
    {
        // Ambil barang yang telah disetujui dan dikategorikan sebagai penambahan stok
        $approvedAdjustments = StockAdjustment::where('type', 'increase')
            ->where('status', 'approved')
            ->with('item') // Memuat data item yang terkait
            ->orderBy('updated_at', 'desc')
            ->paginate(10);



        // Kirim data ke view
        return view('stock_adjustments.incoming', compact('approvedAdjustments'));
    }


    //Tampilan Tambah Stok Barang
    //Fungsi ini tu buat tampilan, jadi pas klik di menu 'Tambah Stok Barang', fungsi ini yang di panggil
    public function increaseView()
    {
        $items = Item::all();
        return view('stock_adjustments.increase', compact('items'));
    }
}
