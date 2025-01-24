@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="fw-bold">Tambah Barang</h1>
    <div class="card shadow-sm p-4 mt-4">
        <form action="{{ route('items.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Barang</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama barang" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stok</label>
                <input type="number" name="stock" id="stock" class="form-control" placeholder="Masukkan stok barang" required>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('items.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
