@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Persetujuan Stok Barang</h1>

    @if(isset($message))
        <div class="alert alert-info">
            {{ $message }}
        </div>
    @elseif($riwayatStock->isEmpty())
        <div class="alert alert-warning">
            Tidak ada pengajuan stok yang perlu disetujui.
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riwayatStock as $stock)
                    <tr>
                        <td>{{ $stock->item->name }}</td>
                        <td>{{ $stock->quantity }}</td>
                        <td>{{ ucfirst($stock->status) }}</td>
                        <td>
                            <form action="{{ route('approveStock', $stock->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-success">Setujui</button>
                            </form>
                            <form action="{{ route('rejectStock', $stock->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-danger">Tolak</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
