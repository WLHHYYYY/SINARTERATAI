<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\StockApprovalController;
use App\Http\Controllers\JenisObatController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PenguranganStokController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root route
Route::get('/', fn() => redirect('/login'));

// Dashboard route
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Authentication routes
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Stock routes
Route::get('/stok', [StokController::class, 'index'])->name('stok.index');
Route::get('/stok/create', [StokController::class, 'create'])->name('stok.create');
Route::post('/stok', [StokController::class, 'store'])->name('stok.store');
Route::get('/riwayat_penambahan', [StokController::class, 'riwayat'])->name('stok.riwayat');

// Stock reduction routes
Route::get('/pengurangan', [PenguranganStokController::class, 'index'])->name('pengurangan.index');
Route::get('/riwayat_pengurangan', [PenguranganStokController::class, 'riwayat'])->name('pengurangan.riwayat');
Route::post('/pengurangan/kurangi', [PenguranganStokController::class, 'kurangiStok'])->name('stok.kurangi');

// Supervisor-only routes
Route::middleware(['auth', 'role:supervisor,direktur']  )->group(function () {
    // Stock approval routes
    Route::get('/stock/approval', [StockApprovalController::class, 'index'])->name('stock.approval');
    Route::patch('/stock/{stock}/approve', [StockApprovalController::class, 'approve'])->name('stock.approve');
    Route::patch('/stock/{stock}/reject', [StockApprovalController::class, 'reject'])->name('stock.reject');

    // Medicine type routes
    Route::resource('jenis_obat', JenisObatController::class);
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::resource('supplier', SupplierController::class);
    Route::resource('barang', BarangController::class);
});

Route::middleware(['auth'])->group(function () {
    // Log Aktivitas Routes
    Route::get('/log-aktivitas', [LogController::class, 'index'])
        ->name('log-aktivitas.index');
});


Route::middleware(['auth', 'role:direktur'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});


// Authentication routes from auth.php
require __DIR__ . '/auth.php';
