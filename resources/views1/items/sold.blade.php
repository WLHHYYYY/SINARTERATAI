@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="fw-bold mb-4">Barang Terjual</h1>

    <!-- Filter Form -->
    <form action="{{ route('stock_adjustments.sold') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">Tanggal Selesai</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($approvedAdjustments as $adjustment)
                    <tr>
                        <td>{{ $adjustment->id }}</td>
                        <td>{{ $adjustment->item->name }}</td>
                        <td>{{ $adjustment->quantity }}</td>
                        <td>{{ $adjustment->updated_at->format('d-m-Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada barang terjual</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

