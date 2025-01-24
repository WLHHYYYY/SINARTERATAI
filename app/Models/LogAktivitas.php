<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'log_aktivitas';

    // Primary key
    protected $primaryKey = 'id';

    // Kolom yang dapat diisi
    protected $fillable = [
        'user_id',
        'aktivitas',
    ];


    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
