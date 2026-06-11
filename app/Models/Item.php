<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'harga',
        'foto',
        'kategori_id'
    ];

    /**
     * Relasi many-to-many dengan Ingredient melalui tabel pivot item_ingredient.
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'item_ingredient')
            ->withPivot('jumlah', 'satuan')
            ->withTimestamps();
    }

    /**
     * Relasi one-to-many dengan transaksi melalui TransactionDetail.
     */
    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_details')
                    ->withPivot('quantity') // Jika ada kolom quantity
                    ->withTimestamps();
    }
}
