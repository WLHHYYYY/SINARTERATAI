@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="fw-bold mb-4">Manajemen Pengguna</h1>

    <!-- Register Link -->
    @if (Route::has('register'))
        <div class="mb-3">
            <a href="{{ route('register') }}" class="btn btn-primary">
                <i class="bi bi-person-plus me-1"></i>Tambah Akun Baru
            </a>
        </div>
    @endif

    <!-- Users Table -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Daftar Pengguna</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Peran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td class="text-center">{{ $users->firstItem() + $index }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <p class="text-muted mb-0">Tidak ada pengguna ditemukan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($users->hasPages())
                <div class="mt-4">
                    {{ $users->links('vendor.pagination.bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
