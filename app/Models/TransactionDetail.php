<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'item_id',
        'quantity',
        'price',
        'total_price',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Relasi dengan Ingredient
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class, 'transaction_detail_id');
    }
}
