@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4 bg-light">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="display-6 h3 mb-0">Pengelolaan Stok Barang</h1>
                                <p class="text-muted mb-0">Monitor dan kelola stok barang Anda</p>
                            </div>
                            <div class="w-25">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" id="searchInput" class="form-control border-start-0 ps-0"
                                        placeholder="Cari Barang..." aria-label="Search">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Table Section -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="stockTable">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="text-center" style="width: 15%">ID Barang</th>
                                        <th style="width: 40%">Nama Barang</th>
                                        <th class="text-center" style="width: 25%">Total Stok</th>
                                        <th class="text-center" style="width: 20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($totalStok as $stok)
                                        <tr>
                                            <td class="text-center fw-bold text-primary">{{ $stok->barang_id }}</td>
                                            <td>{{ $stok->nama_barang }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-{{ $stok->total_stok > 10 ? 'success' : 'warning' }} rounded-pill px-3">
                                                    {{ $stok->total_stok }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-outline-primary btn-sm reduce-stock-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#reduceModal"
                                                    data-barang-id="{{ $stok->barang_id }}"
                                                    data-nama-barang="{{ $stok->nama_barang }}"
                                                    data-total-stok="{{ $stok->total_stok }}">
                                                    <i class="fas fa-minus-circle me-1"></i> Kurangi Stok
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Kurangi Stok -->
        <div class="modal fade" id="reduceModal" tabindex="-1" aria-labelledby="reduceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('stok.kurangi') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h5 class="modal-title" id="reduceModalLabel">
                                <i class="fas fa-boxes me-2"></i>Kurangi Stok Barang
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="barang_id" id="barang_id_hidden">
                            <div class="mb-3">
                                <label for="nama_barang" class="form-label text-muted">Nama Barang</label>
                                <input type="text" id="nama_barang" class="form-control bg-light" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="jumlah" class="form-label text-muted">Jumlah yang Dikurangi</label>
                                <div class="input-group">
                                    <input type="number" name="jumlah" class="form-control" min="1" id="jumlah" required>
                                    <span class="input-group-text">unit</span>
                                </div>
                                <small class="text-muted" id="max-stok-info"></small>
                            </div>
                            <div class="mb-3">
                                <label for="alasan" class="form-label text-muted">Alasan Pengurangan</label>
                                <select name="alasan" id="alasan" class="form-select" required>
                                    <option value="">Pilih alasan...</option>
                                    <option value="penjualan">Penjualan</option>
                                    <option value="expired">Expired</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-1"></i> Konfirmasi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const stockTable = document.getElementById('stockTable');

            // Search functionality
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const filter = this.value.toLowerCase();
                    const rows = stockTable.getElementsByTagName('tr');

                    for (let i = 1; i < rows.length; i++) {
                        const cells = rows[i].getElementsByTagName('td');
                        const found = Array.from(cells).slice(0, -1).some(cell =>
                            cell.textContent.toLowerCase().includes(filter)
                        );
                        rows[i].style.display = found ? '' : 'none';
                    }
                }, 300);
            });

            // Modal population script
            const reduceModal = document.getElementById('reduceModal');
            const stockReduceButtons = document.querySelectorAll('.reduce-stock-btn');

            stockReduceButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const barangId = this.getAttribute('data-barang-id');
                    const namaBarang = this.getAttribute('data-nama-barang');
                    const totalStok = this.getAttribute('data-total-stok');

                    document.getElementById('barang_id_hidden').value = barangId;
                    document.getElementById('nama_barang').value = namaBarang;

                    const jumlahInput = document.getElementById('jumlah');
                    jumlahInput.max = totalStok;

                    const maxStokInfo = document.getElementById('max-stok-info');
                    maxStokInfo.textContent = `Maksimal stok yang bisa dikurangi: ${totalStok} unit`;
                });
            });
        });
    </script>
    @endpush
@endsection
