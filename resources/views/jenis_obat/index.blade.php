<!-- jenis-obat/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Master Jenis Obat</h1>
        <a href="{{ route('jenis_obat.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Jenis
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Jenis</th>
                            <th>Deskripsi</th>
                            <th>Created At</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jenisObat as $jenis)
                        <tr>
                            <td>{{ $jenis->id }}</td>
                            <td>{{ $jenis->nama_jenis }}</td>
                            <td>{{ $jenis->deskripsi }}</td>
                            <td>{{ $jenis->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <form action="{{ route('jenis_obat.destroy', $jenis->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
