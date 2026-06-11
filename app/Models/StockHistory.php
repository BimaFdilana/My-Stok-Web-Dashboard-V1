<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingredient_id',
        'stock_id',
        'jumlah',
        'unit',
        'tanggal',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'datetime'
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    // Accessor untuk mendapatkan kategori melalui stock
    public function getCategoryAttribute()
    {
        return $this->stock->category ?? null;
    }
}
