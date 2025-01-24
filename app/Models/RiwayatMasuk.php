<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatMasuk extends Model
{
    protected $fillable = [
        'stok_id', 'jumlah_masuk', 'tanggal_masuk', 'diajukan_oleh', 'disetujui_oleh',
    ];

    public function stok()
    {
        return $this->belongsTo(Stok::class);
    }
}
