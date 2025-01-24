<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Stok;
use App\Models\RiwayatPengurangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenguranganStokController extends Controller
{
    public function index(Request $request)
    {
        $totalStok = Barang::withSum(['stok' => function ($query) {
            $query->where('status', 'approved');
        }], 'jumlah')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'barang_id' => $item->id,
                    'nama_barang' => $item->nama_barang,
                    'total_stok' => $item->stok_sum_jumlah ?? 0,
                ];
            });

        $pengurangan = RiwayatPengurangan::with(['stok.barang', 'user'])->paginate(15);;

        return view('pengurangan.index', compact('totalStok', 'pengurangan'));
    }

    public function riwayat(Request $request)
    {
        $totalStok = Barang::withSum(['stok' => function ($query) {
            $query->where('status', 'approved');
        }], 'jumlah')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'barang_id' => $item->id,
                    'nama_barang' => $item->nama_barang,
                    'total_stok' => $item->stok_sum_jumlah ?? 0,
                ];
            });

        $pengurangan = RiwayatPengurangan::with(['stok.barang', 'user'])->paginate(15);;


        return view('pengurangan.riwayat', compact('totalStok', 'pengurangan'));
    }

    public function kurangiStok(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'alasan' => 'required|string',
        ]);

        $barangId = $request->barang_id;
        $jumlahDikurangi = $request->jumlah;
        $alasan = $request->alasan;
        $userId = Auth::id();

        $barang = Barang::findOrFail($barangId);

        $stokList = Stok::where('barang_id', $barangId)
            ->where('status', 'approved')
            ->orderBy('tanggal_expired', 'asc')
            ->get();

        foreach ($stokList as $stok) {
            if ($jumlahDikurangi <= 0) {
                break;
            }

            if ($stok->jumlah == 0) {
                continue;
            }

            $jumlahAwal = $stok->jumlah;
            $pengurangan = min($jumlahAwal, $jumlahDikurangi);

            $stok->jumlah -= $pengurangan;
            $stok->save();

            RiwayatPengurangan::create([
                'stok_id' => $stok->id,
                'jumlah_awal' => $jumlahAwal,
                'jumlah_dikurangi' => $pengurangan,
                'jumlah_akhir' => $stok->jumlah,
                'alasan' => $alasan,
                'dikurangi_oleh' => $userId,
            ]);

            $jumlahDikurangi -= $pengurangan;
        }

        $this->createLogActivity("Mengurangi stok barang {$barang->nama_barang} sebanyak {$request->jumlah} dengan alasan: {$alasan}");

        return redirect()->route('pengurangan.index')->with('success', 'Stok berhasil dikurangi');
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
