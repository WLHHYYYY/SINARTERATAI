<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_pengurangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_id')->constrained('stoks');
            $table->integer('jumlah_awal');
            $table->integer('jumlah_dikurangi');
            $table->integer('jumlah_akhir');
            $table->enum('alasan', ['penjualan', 'expired', 'lainnya']);
            $table->foreignId('dikurangi_oleh')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pengurangans');
    }
};
