@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="fw-bold mb-4">Log Aktivitas</h1>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('log-aktivitas.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-3">
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-select">
                        <option value="">Semua User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('log-aktivitas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>User</th>
                            <th>Aktivitas</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $index => $log)
                        <tr>
                            <td class="text-center">{{ $logs->firstItem() + $index }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $log->user->name }}</span>
                            </td>
                            <td>{{ $log->aktivitas }}</td>
                            <td>
                                {{ $log->created_at }}
                                <small class="text-muted d-block">
                                    {{-- {{ $log->created_at->diffForHumans() }} --}}
                                </small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($logs->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-muted">Tidak ada log aktivitas ditemukan</p>
                    </div>
                @endif

                {{-- {{ $logs->appends(request()->query())->links('vendor.pagination.bootstrap-5') }} --}}
            </div>
        </div>
    </div>
</div>
@endsection
