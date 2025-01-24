@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h3 mb-0">Data Stok</h1>
                            <p class="text-muted mb-0">Kelola dan monitoring data stok barang</p>
                        </div>
                        <div>
                            <a href="{{ route('stok.create') }}" class="btn btn-success">
                                <i class="fas fa-plus me-1"></i> Tambah Stok
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        @if ($stoks->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Data stok belum tersedia.</p>
                            </div>
                        @else
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Barang</th>
                                        <th>Supplier</th>
                                        <th class="text-center">Jumlah</th>
                                        <th>Tanggal Expired</th>
                                        <th>Diajukan Oleh</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th class="text-center">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stoks as $stok)
                                        <tr>
                                            <td>{{ $stok->id }}</td>
                                            <td>{{ $stok->barang->nama_barang }}</td>
                                            <td>{{ $stok->supplier->nama_supplier }}</td>
                                            <td class="text-center">
                                                @php
                                                    $badgeClass = 'bg-success';
                                                    if ($stok->jumlah <= 10) {
                                                        $badgeClass = 'bg-danger';
                                                    } elseif ($stok->jumlah <= 20) {
                                                        $badgeClass = 'bg-warning';
                                                    }
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $stok->jumlah }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $today = \Carbon\Carbon::now();
                                                    $expired = \Carbon\Carbon::parse($stok->tanggal_expired);
                                                    $daysUntilExpired = $today->diffInDays($expired, false);

                                                    $textClass = 'text-success';
                                                    if ($daysUntilExpired < 0) {
                                                        $textClass = 'text-danger fw-bold';
                                                    } elseif ($daysUntilExpired <= 30) {
                                                        $textClass = 'text-warning';
                                                    }
                                                @endphp
                                                <span class="{{ $textClass }}">
                                                    {{ $stok->tanggal_expired->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td>{{ $stok->diajukanOleh->name }}</td>
                                            <td>{{ $stok->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailModal{{ $stok->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Detail Modal -->
                                        <div class="modal fade" id="detailModal{{ $stok->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            Detail Stok
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label text-muted">Diproses Oleh</label>
                                                                <p class="mb-0">{{ $stok->disetujuiOleh->name ?? '-' }}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label text-muted">Tanggal Diproses</label>
                                                                <p class="mb-0">{{ $stok->updated_at->format('d/m/Y H:i') }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            Tutup
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
