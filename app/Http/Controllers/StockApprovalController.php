<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StockApprovalController extends Controller
{
    /**
     * Display a listing of the stocks pending approval
     */
    public function index()
    {
        $stocks = Stok::with(['barang', 'supplier', 'diajukanOleh'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('stok.approval', compact('stocks'));
    }

    /**
     * Approve the specified stock request
     */
    public function approve(Stok $stock)
    {
        if ($stock->status !== 'pending') {
            return redirect()->back()->with('error', 'Stok ini sudah diproses sebelumnya.');
        }

        $stock->update([
            'status' => 'approved',
            'disetujui_oleh' => Auth::id(),
        ]);

        // Create log activity
        $this->createLogActivity('Menyetujui pengajuan stok ' . $stock->barang->nama_barang);

        return redirect()->back()->with('success', 'Pengajuan stok berhasil disetujui.');
    }

    /**
     * Reject the specified stock request
     */
    public function reject(Request $request, Stok $stock)
    {
        if ($stock->status !== 'pending') {
            return redirect()->back()->with('error', 'Stok ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'alasan_penolakan' => 'required|string|max:255',
        ]);

        $stock->update([
            'status' => 'rejected',
            'alasan_penolakan' => $request->alasan_penolakan,
            'disetujui_oleh' => Auth::id(),
        ]);

        // Create log activity
        $this->createLogActivity('Menolak pengajuan stok ' . $stock->barang->nama_barang);

        return redirect()->back()->with('success', 'Pengajuan stok telah ditolak.');
    }

    /**
     * Create a log activity
     */
    private function createLogActivity($aktivitas)
    {
        \App\Models\LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => $aktivitas,
        ]);
    }
}
