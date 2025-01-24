@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Riwayat Pengajuan Stok</h1>
                <p class="text-muted small mb-0">Kelola dan pantau status pengajuan stok</p>
            </div>
            <a href="{{ route('stok.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Stok
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('stok.riwayat') }}" class="mb-3">
            <div class="row g-2">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Table Card -->
        <div class="table-responsive">
            @if ($stoks->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-box fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Data pengajuan stok belum tersedia.</p>
                </div>
            @else
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" style="width: 80px">ID</th>
                            <th>Nama Barang</th>
                            <th>Supplier</th>
                            <th class="text-center">Jumlah</th>
                            <th>Tanggal Expired</th>
                            <th class="text-center">Status</th>
                            <th>Diajukan Oleh</th>
                            <th>Tanggal Pengajuan</th>
                            <th class="text-center" style="width: 100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stoks as $stok)
                            <tr>
                                <td class="text-center"><strong>#{{ $stok->id }}</strong></td>
                                <td>{{ $stok->barang->nama_barang }}</td>
                                <td>{{ $stok->supplier->nama_supplier }}</td>
                                <td class="text-center">{{ number_format($stok->jumlah_display) }}</td>
                                <td>{{ $stok->tanggal_expired->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-{{ $stok->status == 'pending' ? 'warning' : ($stok->status == 'approved' ? 'success' : 'danger') }}">
                                        {{ ucfirst($stok->status) }}
                                    </span>
                                </td>
                                <td>{{ $stok->diajukanOleh->name }}</td>
                                <td>{{ $stok->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $stok->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Pagination Controls -->
        <div class="d-flex justify-content-end mt-3">
            {{ $stoks->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
