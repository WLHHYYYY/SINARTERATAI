<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'nama_supplier',
        'alamat',
        'kontak'
    ];

    public function stok()
    {
        return $this->hasMany(Stok::class);
    }
}
