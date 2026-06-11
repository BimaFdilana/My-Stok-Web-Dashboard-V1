<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\StockHistory;
use App\Models\Stock;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $today = Carbon::today();

        $trx1 = Transaction::create([
            'user_id' => 1,
            'total' => 36000,
            'payment_method' => 'cash',
            'created_at' => $today->copy()->setTime(8, 30),
            'updated_at' => $today->copy()->setTime(8, 30),
        ]);

        TransactionDetail::insert([
            ['transaction_id' => $trx1->id, 'item_id' => 1, 'quantity' => 2, 'price' => 18000, 'total_price' => 36000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $trx2 = Transaction::create([
            'user_id' => 1,
            'total' => 23000,
            'payment_method' => 'qris',
            'created_at' => $today->copy()->setTime(9, 15),
            'updated_at' => $today->copy()->setTime(9, 15),
        ]);

        TransactionDetail::insert([
            ['transaction_id' => $trx2->id, 'item_id' => 2, 'quantity' => 1, 'price' => 15000, 'total_price' => 15000, 'created_at' => now(), 'updated_at' => now()],
            ['transaction_id' => $trx2->id, 'item_id' => 3, 'quantity' => 1, 'price' => 8000, 'total_price' => 8000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $trx3 = Transaction::create([
            'user_id' => 2,
            'total' => 53000,
            'payment_method' => 'cash',
            'created_at' => $today->copy()->setTime(10, 0),
            'updated_at' => $today->copy()->setTime(10, 0),
        ]);

        TransactionDetail::insert([
            ['transaction_id' => $trx3->id, 'item_id' => 4, 'quantity' => 1, 'price' => 20000, 'total_price' => 20000, 'created_at' => now(), 'updated_at' => now()],
            ['transaction_id' => $trx3->id, 'item_id' => 1, 'quantity' => 1, 'price' => 18000, 'total_price' => 18000, 'created_at' => now(), 'updated_at' => now()],
            ['transaction_id' => $trx3->id, 'item_id' => 5, 'quantity' => 1, 'price' => 15000, 'total_price' => 15000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $trx4 = Transaction::create([
            'user_id' => 1,
            'total' => 40000,
            'payment_method' => 'qris',
            'created_at' => $today->copy()->setTime(12, 30),
            'updated_at' => $today->copy()->setTime(12, 30),
        ]);

        TransactionDetail::insert([
            ['transaction_id' => $trx4->id, 'item_id' => 4, 'quantity' => 2, 'price' => 20000, 'total_price' => 40000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $trx5 = Transaction::create([
            'user_id' => 2,
            'total' => 46000,
            'payment_method' => 'cash',
            'created_at' => $today->copy()->subDay()->setTime(14, 0),
            'updated_at' => $today->copy()->subDay()->setTime(14, 0),
        ]);

        TransactionDetail::insert([
            ['transaction_id' => $trx5->id, 'item_id' => 1, 'quantity' => 1, 'price' => 18000, 'total_price' => 18000, 'created_at' => now(), 'updated_at' => now()],
            ['transaction_id' => $trx5->id, 'item_id' => 3, 'quantity' => 1, 'price' => 8000, 'total_price' => 8000, 'created_at' => now(), 'updated_at' => now()],
            ['transaction_id' => $trx5->id, 'item_id' => 4, 'quantity' => 1, 'price' => 20000, 'total_price' => 20000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->createStockHistories($trx1, $today->copy()->setTime(8, 30));
        $this->createStockHistories($trx2, $today->copy()->setTime(9, 15));
        $this->createStockHistories($trx3, $today->copy()->setTime(10, 0));
    }

    private function createStockHistories($transaction, $tanggal)
    {
        foreach ($transaction->details as $detail) {
            $item = $detail->item;
            if (!$item) continue;

            foreach ($item->ingredients as $ingredient) {
                $required = $ingredient->pivot->jumlah * $detail->quantity;
                $stock = Stock::where('ingredient_id', $ingredient->id)->first();

                if ($stock) {
                    $stock->decrement('jumlah', $required);

                    StockHistory::create([
                        'ingredient_id' => $ingredient->id,
                        'stock_id' => $stock->id,
                        'jumlah' => $required,
                        'unit' => $ingredient->pivot->satuan,
                        'tanggal' => $tanggal,
                        'keterangan' => 'Penggunaan transaksi #' . $transaction->id,
                    ]);
                }
            }
        }
    }
}
