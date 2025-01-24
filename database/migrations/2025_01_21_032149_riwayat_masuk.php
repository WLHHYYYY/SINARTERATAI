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
        Schema::create('riwayat_masuks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stok_id');
            $table->integer('jumlah_masuk');
            $table->timestamp('tanggal_masuk');
            $table->unsignedBigInteger('diajukan_oleh'); // ID user yang menambahkan barang
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users');

            $table->timestamps();

            $table->foreign('stok_id')->references('id')->on('stoks')->onDelete('cascade');
            $table->foreign('diajukan_oleh')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_masuk');
    }
};
