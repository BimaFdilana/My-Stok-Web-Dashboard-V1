<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'stok',
        'satuan',
        'kategori_id'
    ];

    /**
     * Relasi many-to-many dengan Item melalui tabel pivot item_ingredient.
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_ingredient')
            ->withPivot('jumlah', 'satuan') // Tambahkan kolom tambahan dari tabel pivot
            ->withTimestamps(); // Menyimpan waktu created_at dan updated_at
    }

    /**
     * Relasi one-to-many dengan barang masuk.
     */
    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }

    /**
     * Relasi one-to-many dengan stok.
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'ingredient_id');
    }

    /**
     * Relasi one-to-many dengan detail transaksi.
     */
    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'ingredient_id');
    }

    /**
     * Relasi one-to-one dengan Category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }
}