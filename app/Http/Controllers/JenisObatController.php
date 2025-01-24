<?php

namespace App\Http\Controllers;

use App\Models\JenisObat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisObatController extends Controller
{
    public function index()
    {
        $jenisObat = JenisObat::all();
        return view('jenis_obat.index', compact('jenisObat'));
    }

    public function create()
    {
        return view('jenis_obat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|unique:jenis_obats',
            'deskripsi' => 'nullable',
        ]);

        $jenisObat = JenisObat::create([
            'nama_jenis' => $request->nama_jenis,
            'deskripsi' => $request->deskripsi,
        ]);

        // Log activity for creating a new jenis obat
        $this->createLogActivity("Menambahkan jenis obat baru: {$request->nama_jenis}");

        return redirect()->route('jenis_obat.index')->with('success', 'Jenis obat berhasil ditambahkan.');
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
