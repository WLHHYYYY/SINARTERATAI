<!-- barang/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Tambah Barang</h1>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('barang.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text"
                                   class="form-control @error('nama_barang') is-invalid @enderror"
                                   id="nama_barang"
                                   name="nama_barang"
                                   value="{{ old('nama_barang') }}"
                                   placeholder="Masukkan nama barang"
                                   required>
                            @error('nama_barang')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jenis_id" class="form-label">Jenis Obat</label>
                            <select name="jenis_id"
                                    id="jenis_id"
                                    class="form-select @error('jenis_id') is-invalid @enderror"
                                    required>
                                <option value="">Pilih Jenis Obat</option>
                                @foreach ($jenisObat as $jenis)
                                    <option value="{{ $jenis->id }}"
                                            {{ old('jenis_id') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama_jenis }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                      id="deskripsi"
                                      name="deskripsi"
                                      rows="3"
                                      placeholder="Masukkan deskripsi barang">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
