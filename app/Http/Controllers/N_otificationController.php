<?php

namespace App\Http\Controllers;

use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

//fitur notifikasi
class N_otificationController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = StockAdjustment::query();

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date . ' 00:01:00');
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        // Filter Jenis Transaksi
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $notifications = $query->with('item')->get();

        return view('notifications.index', compact('notifications'));
    }

    //fitur download pdf(lanjutan notif)
    public function downloadPDF(Request $request)
    {
        $query = StockAdjustment::query();

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date . ' 00:01:00');
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        // Filter Jenis Transaksi
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $notifications = $query->with('item')->get();

        $pdf = PDF::loadView('notifications.pdf', compact('notifications'));
        return $pdf->download('notifikasi_barang.pdf');
    }
}
