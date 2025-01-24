<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = 'stoks';

    protected $fillable = [
        'barang_id',
        'supplier_id',
        'jumlah',
        'tanggal_expired',
        'status',
        'alasan_penolakan',
        'diajukan_oleh',
        'disetujui_oleh',
        'tanggal_pengajuan',
        'tanggal_persetujuan'
    ];

    protected $casts = [
        'tanggal_expired' => 'date',
        'tanggal_pengajuan' => 'datetime',
        'tanggal_persetujuan' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function diajukanOleh()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function riwayatPengurangan()
    {
        return $this->hasMany(RiwayatPengurangan::class, 'stok_id');
    }


    public static function boot()
    {
        parent::boot();

        static::updated(function ($stok) {
            if ($stok->isDirty('status') && $stok->status === 'approved') {
                \App\Models\RiwayatMasuk::create([
                    'stok_id' => $stok->id,
                    'jumlah_masuk' => $stok->jumlah,
                    'tanggal_masuk' => now(),
                    'diajukan_oleh' => $stok->diajukan_oleh,
                    'disetujui_oleh' => $stok->disetujui_oleh,
                ]);
            }
        });
    }

    public function getDisplayJumlahAttribute()
    {
        if ($this->status == 'approved') {
            return $this->riwayatMasuk ? $this->riwayatMasuk->jumlah_masuk : 0;
        }
        return $this->jumlah;
    }

    public function riwayatMasuk()
    {
        return $this->hasOne(RiwayatMasuk::class);
    }
}
