<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPengurangan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stok_id',
        'jumlah_awal',
        'jumlah_dikurangi',
        'jumlah_akhir',
        'alasan',
        'tanggal_pengurangan',
        'dikurangi_oleh',
    ];

    public function stok()
    {
        return $this->belongsTo(Stok::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'dikurangi_oleh');
    }

}
