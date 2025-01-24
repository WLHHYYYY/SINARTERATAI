@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="fw-bold">Pengaturan Stok</h1>
    <div class="card shadow-sm p-4 mt-4">
        <form action="{{ route('stock_adjustments.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="item_id" class="form-label">Barang</label>
                <select name="item_id" id="item_id" class="form-control" required>
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}">{{ $item->name }} (Stok: {{ $item->stock }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Jumlah</label>
                <input type="number" name="quantity" id="quantity" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Tipe</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="increase">Tambah Stok</option>
                    <option value="decrease">Kurangi Stok</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Proses</button>
        </form>
    </div>
</div>
@endsection
