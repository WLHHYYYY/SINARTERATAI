@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">Hai, Selamat datang kembali {{ Auth::user()->name }}!</h1>
                    <p class="text-lg">Kami senang melihat Anda kembali. Jelajahi fitur baru dan tetap produktif!</p>
                    <div class="mt-6">
                        <a href="{{ route('stok.index') }}" class="btn btn-primary">Lihat Stok</a>
                        <a href="{{ route('stok.create') }}" class="btn btn-success ml-2">Tambah Stok</a>
                        <a href="{{ route('stok.riwayat') }}" class="btn btn-info ml-2">Riwayat Penambahan</a>
                        <a href="{{ route('pengurangan.index') }}" class="btn btn-warning ml-2">Pengurangan Stok</a>
                        <a href="{{ route('pengurangan.riwayat') }}" class="btn btn-secondary ml-2">Riwayat Pengurangan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
