@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="card-title mb-0 fw-bold">Persetujuan Stok Barang</h4>
        </div>

        <div class="card-body">
            @if (session('message'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($stocks->isEmpty())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Tidak ada pengajuan stok yang perlu disetujui.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap">Nama Barang</th>
                                <th class="text-nowrap">Supplier</th>
                                <th class="text-nowrap">Jumlah</th>
                                <th class="text-nowrap">Tanggal Expired</th>
                                <th class="text-nowrap">Status</th>
                                <th class="text-nowrap">Diajukan Oleh</th>
                                <th class="text-nowrap">Tanggal Pengajuan</th>
                                <th class="text-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stocks as $stock)
                                <tr>
                                    <td class="text-nowrap">{{ $stock->barang->nama_barang }}</td>
                                    <td class="text-nowrap">{{ $stock->supplier->nama_supplier }}</td>
                                    <td class="text-nowrap">{{ number_format($stock->jumlah) }}</td>
                                    <td class="text-nowrap">{{ \Carbon\Carbon::parse($stock->tanggal_expired)->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{
                                            $stock->status === 'pending' ? 'warning' :
                                            ($stock->status === 'approved' ? 'success' : 'danger')
                                        }}">
                                            {{ ucfirst($stock->status) }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap">{{ $stock->diajukanOleh->name }}</td>
                                    <td class="text-nowrap">{{ $stock->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        @if ($stock->status === 'pending')
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('stock.approve', $stock->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success btn-sm me-1">
                                                        <i class="bi bi-check-lg me-1"></i>Setujui
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $stock->id }}">
                                                    <i class="bi bi-x-lg me-1"></i>Tolak
                                                </button>
                                            </div>

                                            <!-- Modal Penolakan -->
                                            <div class="modal fade" id="rejectModal{{ $stock->id }}" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('stock.reject', $stock->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="alasan_penolakan" class="form-label">Alasan Penolakan:</label>
                                                                    <textarea name="alasan_penolakan" id="alasan_penolakan" class="form-control" rows="3" required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                    <i class="bi bi-x-circle me-1"></i>Batal
                                                                </button>
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="bi bi-exclamation-circle me-1"></i>Tolak Pengajuan
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @if ($stock->status === 'rejected' && $stock->alasan_penolakan)
                                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#reasonModal{{ $stock->id }}">
                                                    <i class="bi bi-info-circle me-1"></i>Lihat Alasan
                                                </button>

                                                <!-- Modal Alasan -->
                                                <div class="modal fade" id="reasonModal{{ $stock->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Alasan Penolakan</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="alert alert-secondary mb-0">
                                                                    {{ $stock->alasan_penolakan }}
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                    <i class="bi bi-x-circle me-1"></i>Tutup
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
@endpush
