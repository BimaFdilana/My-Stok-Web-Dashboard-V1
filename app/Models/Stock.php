<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingredient_id',
        'category_id',
        'jumlah',
        'jumlah_awal',
        'tanggal',
        'satuan'
    ];

    protected $casts = [
        'tanggal' => 'datetime'
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function histories()
    {
        return $this->hasMany(StockHistory::class);
    }

    public function getTanggalAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
}