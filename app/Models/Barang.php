<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Barang extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_barang',
        'jenis_id',
        'deskripsi',
        'created_by'
    ];

    public function jenisObat()
    {
        return $this->belongsTo(JenisObat::class, 'jenis_id');
    }

    public function stok()
    {
        return $this->hasMany(Stok::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
