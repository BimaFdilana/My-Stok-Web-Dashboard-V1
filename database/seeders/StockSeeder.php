<?php

namespace Database\Seeders;

use App\Models\Stock;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run()
    {
        $stocks = [
            ['ingredient_id' => 1, 'category_id' => 1, 'jumlah' => 500, 'jumlah_awal' => 500, 'satuan' => 'gram', 'tanggal' => now()->subDays(5), 'created_at' => now(), 'updated_at' => now()],
            ['ingredient_id' => 2, 'category_id' => 1, 'jumlah' => 2000, 'jumlah_awal' => 2000, 'satuan' => 'gram', 'tanggal' => now()->subDays(5), 'created_at' => now(), 'updated_at' => now()],
            ['ingredient_id' => 3, 'category_id' => 1, 'jumlah' => 5000, 'jumlah_awal' => 5000, 'satuan' => 'ml', 'tanggal' => now()->subDays(3), 'created_at' => now(), 'updated_at' => now()],
            ['ingredient_id' => 4, 'category_id' => 3, 'jumlah' => 100, 'jumlah_awal' => 100, 'satuan' => 'pcs', 'tanggal' => now()->subDays(3), 'created_at' => now(), 'updated_at' => now()],
            ['ingredient_id' => 5, 'category_id' => 3, 'jumlah' => 100, 'jumlah_awal' => 100, 'satuan' => 'pcs', 'tanggal' => now()->subDays(2), 'created_at' => now(), 'updated_at' => now()],
            ['ingredient_id' => 6, 'category_id' => 1, 'jumlah' => 50, 'jumlah_awal' => 50, 'satuan' => 'pcs', 'tanggal' => now()->subDays(2), 'created_at' => now(), 'updated_at' => now()],
            ['ingredient_id' => 7, 'category_id' => 1, 'jumlah' => 3000, 'jumlah_awal' => 3000, 'satuan' => 'gram', 'tanggal' => now()->subDay(), 'created_at' => now(), 'updated_at' => now()],
            ['ingredient_id' => 8, 'category_id' => 1, 'jumlah' => 1000, 'jumlah_awal' => 1000, 'satuan' => 'gram', 'tanggal' => now()->subDay(), 'created_at' => now(), 'updated_at' => now()],
        ];

        Stock::insert($stocks);
    }
}
