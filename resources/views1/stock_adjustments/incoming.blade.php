@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="fw-bold mb-4">Barang Masuk</h1>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th> <!-- Kolom Urutan -->
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Stok Sebelum</th>
                    <th>Stok Sesudah</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($approvedAdjustments as $index => $adjustment)
                    <tr>
                        <td>{{ $index + 1 + ($approvedAdjustments->currentPage() - 1) * $approvedAdjustments->perPage() }}</td> <!-- Menampilkan urutan dengan mempertimbangkan halaman -->
                        <td>{{ $adjustment->id }}</td>
                        <td>{{ $adjustment->item->name }}</td>
                        <td>{{ $adjustment->quantity }}</td>
                        <td>{{ $adjustment->item->stock - $adjustment->quantity }}</td> <!-- Stok Sebelum -->
                        <td>{{ $adjustment->item->stock }}</td> <!-- Stok Sesudah -->
                        <td>{{ $adjustment->updated_at->format('d-m-Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada barang masuk</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $approvedAdjustments->links('pagination::bootstrap-4') }} <!-- Memperbaiki tampilan pagination menggunakan bootstrap-4 -->
        </div>
    </div>
</div>
@endsection
