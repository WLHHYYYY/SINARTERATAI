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
                                <h1 class="display-6 h3 mb-0">Data Obat</h1>
                                <p class="text-muted mb-0">Kelola dan monitoring data obat</p>
                            </div>
                            <a href="{{ route('barang.create') }}" class="btn btn-success">
                                <i class="fas fa-plus me-1"></i> Tambah obat
                            </a>
                            <div class="w-25">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" id="searchInput" class="form-control border-start-0 ps-0"
                                        placeholder="Cari Obat..." aria-label="Search">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="obatTable">
                                <thead>
                                    <tr class="bg-light">
                                        <th width="5%">ID</th>
                                        <th width="20%">Nama Obat</th>
                                        <th width="15%">Jenis</th>
                                        <th width="25%">Deskripsi</th>
                                        <th width="15%">Created By</th>
                                        <th width="10%">Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barang as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td class="fw-medium">{{ $item->nama_barang }}</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $item->jenisObat->nama_jenis }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>{{ $item->deskripsi ?: 'Tidak ada deskripsi' }}</small>
                                            </td>
                                            <td>{{ $item->createdBy->name }}</td>
                                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if ($barang->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-pills fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Data obat belum tersedia.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Ambil elemen input dan tabel
        const searchInput = document.getElementById('searchInput');
        const obatTable = document.getElementById('obatTable');

        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase(); // Ambil nilai input dan ubah ke lowercase
            const rows = obatTable.getElementsByTagName('tr'); // Ambil semua baris tabel

            for (let i = 1; i < rows.length; i++) { // Iterasi semua baris kecuali header
                const namaObatCell = rows[i].getElementsByTagName('td')[1]; // Ambil kolom "Nama Obat"

                // Tampilkan baris jika teks "Nama Obat" cocok dengan pencarian
                if (namaObatCell && namaObatCell.textContent.toLowerCase().includes(filter)) {
                    rows[i].style.display = ''; // Tampilkan baris
                } else {
                    rows[i].style.display = 'none'; // Sembunyikan baris
                }
            }
        });
    </script>
@endsection
