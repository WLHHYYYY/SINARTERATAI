@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="m-0 text-dark">
                            <i class="fas fa-clipboard-list text-primary me-2"></i>
                            Riwayat Pengurangan Stok
                        </h4>
                        <div class="d-flex align-items-center">
                            <input type="date" id="startDate" class="form-control form-control-sm me-2"
                                   placeholder="Tanggal Mulai">
                            <input type="date" id="endDate" class="form-control form-control-sm me-2"
                                   placeholder="Tanggal Akhir">
                            <button class="btn btn-outline-secondary btn-sm" id="filterButton">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <button class="btn btn-outline-primary btn-sm ms-2" onclick="window.print()">
                                <i class="fas fa-print me-1"></i> Cetak
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0" id="stockReductionTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center align-middle">ID</th>
                                    <th class="align-middle">Nama Barang</th>
                                    <th class="text-center align-middle">Stok Awal</th>
                                    <th class="text-center align-middle">Dikurangi</th>
                                    <th class="text-center align-middle">Stok Akhir</th>
                                    <th class="align-middle">Alasan</th>
                                    <th class="align-middle">Tanggal</th>
                                    <th class="align-middle">Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengurangan as $p)
                                    <tr data-date="{{ $p->created_at->format('Y-m-d') }}">
                                        <td class="text-center">{{ $p->id }}</td>
                                        <td>
                                            <span class="fw-bold text-primary">
                                                {{ $p->stok->barang->nama_barang }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                {{ $p->jumlah_awal }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger bg-opacity-10 text-danger">
                                                -{{ $p->jumlah_dikurangi }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                {{ $p->jumlah_akhir }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge
                                                {{ $p->alasan == 'Expired'
                                                    ? 'bg-warning text-dark'
                                                    : ($p->alasan == 'Penjualan'
                                                        ? 'bg-success text-white'
                                                        : ($p->alasan == 'Rusak'
                                                            ? 'bg-danger text-white'
                                                            : 'bg-secondary text-white')) }}">
                                                {{ $p->alasan }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $p->created_at }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                                {{ $p->user->name }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Tidak ada riwayat pengurangan stok</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls -->
                    <div class="d-flex justify-content-end mt-3">
                        {{ $pengurangan->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    #stockReductionTable {
        font-size: 0.95rem;
    }

    #stockReductionTable th {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge {
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('filterButton').addEventListener('click', function() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        if (!startDate && !endDate) {
            alert("Harap isi minimal salah satu tanggal filter!");
            return;
        }

        const start = startDate ? new Date(startDate) : null;
        const end = endDate ? new Date(endDate) : null;

        const rows = document.querySelectorAll('#stockReductionTable tbody tr');

        rows.forEach(row => {
            const dateString = row.getAttribute('data-date');
            const date = new Date(dateString);

            if ((!start || date >= start) && (!end || date <= end)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endpush
