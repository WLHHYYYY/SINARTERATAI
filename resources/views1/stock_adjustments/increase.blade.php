@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="fw-bold mb-4">Tambah Stok Barang</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('stock_adjustments.increase.post') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="item_id" class="form-label">Pilih Barang</label>
            <select name="item_id" id="item_id" class="form-control" required>
                <option value="" disabled selected>Pilih barang</option>
                @foreach ($items as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Jumlah</label>
            <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Masukkan jumlah stok" required>
        </div>

        <button type="submit" class="btn btn-primary">Tambah Stok</button>
    </form>
</div>
@endsection
