<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{

    use HasFactory;

    protected $table = 'riwayat_stock';

    protected $fillable = ['item_id', 'quantity', 'type', 'status'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
