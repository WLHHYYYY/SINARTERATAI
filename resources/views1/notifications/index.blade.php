@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold">Notifikasi Barang</h1>
            <!-- Tombol Unduh PDF dan Tombol Filter -->
            <div class="d-flex gap-2">
                <a href="{{ route('notifications.download', request()->all()) }}" class="btn btn-danger btn-lg">
                    <i class="fas fa-file-pdf"></i> Unduh PDF
                </a>
                <button class="btn btn-secondary btn-lg" type="button" data-bs-toggle="collapse"
                    data-bs-target="#filterSection" aria-expanded="false" aria-controls="filterSection">
                    <i class="fas fa-filter"></i> Filter Pencarian
                </button>
            </div>
        </div>

        <!-- Form Filter -->
        <div class="collapse" id="filterSection">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-semibold">Filter Pencarian</h5>
                    <form method="GET" action="{{ route('notifications.index') }}">
                        <div class="row g-3">
                            <!-- Filter Tanggal -->
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">Dari Tanggal:</label>
                                <input type="date" id="start_date" name="start_date" class="form-control"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">Sampai Tanggal:</label>
                                <input type="date" id="end_date" name="end_date" class="form-control"
                                    value="{{ request('end_date') }}">
                            </div>

                            <!-- Filter Jenis Transaksi -->
                            <div class="col-md-3">
                                <label for="type" class="form-label">Jenis Transaksi:</label>
                                <select id="type" name="type" class="form-select">
                                    <option value="" {{ request('type') === null ? 'selected' : '' }}>Semua</option>
                                    <option value="increase" {{ request('type') === 'increase' ? 'selected' : '' }}>Barang Masuk</option>
                                    <option value="decrease" {{ request('type') === 'decrease' ? 'selected' : '' }}>Barang Keluar</option>
                                </select>
                            </div>

                            <!-- Filter Status -->
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status:</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="" {{ request('status') === null ? 'selected' : '' }}>Semua</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter"></i> Terapkan Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Notifikasi -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3 fw-semibold">Daftar Notifikasi</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th>#</th>
                                <th>Barang</th>
                                <th>Jenis Transaksi</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($notifications as $notification)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $notification->item->name }}</td>
                                    <td class="text-center">
                                        @if ($notification->type === 'increase')
                                            <span class="badge bg-success">Masuk</span>
                                        @else
                                            <span class="badge bg-danger">Keluar</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $notification->quantity }}</td>
                                    <td class="text-center">
                                        @if ($notification->status === 'pending')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @elseif ($notification->status === 'approved')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $notification->created_at->format('d-m-Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada notifikasi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
