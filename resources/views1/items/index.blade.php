@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold">Stok Barang Terkini</h1>
            @if (auth()->check() && auth()->user()->role === 'supervisor')
                <a href="{{ route('items.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Tambah Barang
                </a>
            @endif
        </div>


        <!-- Pencarian Barang -->
        <form method="GET" action="{{ route('items.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari barang..."
                    value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </form>

        <!-- Notifikasi -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tabel Barang -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered w-100">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Stok Tersedia</th>
                        <th>Stok Menunggu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                             <!--stok akan merah kalau dibawah 50 -->
                            <td class="text-center">{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <span>{{ $item->stock }}</span>
                                    @if ($item->stock < 50)
                                        <span class="badge bg-danger ms-2">Rendah</span>
                                    @endif
                                </div>
                            </td>


                            <td class="text-center">
                                @if ($item->reserved_stock > 0)
                                    <span class="badge bg-warning text-dark">{{ $item->reserved_stock }}</span>
                                @else
                                    <span class="text-muted">0</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('stock_adjustments.reduce') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    <div class="input-group">
                                        <input type="number" name="quantity" class="form-control form-control-sm"
                                            placeholder="Jumlah" required>
                                        <button class="btn btn-primary btn-sm">
                                            <i class="fas fa-minus"></i> Kurangi
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada data barang</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
