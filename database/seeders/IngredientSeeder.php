<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run()
    {
        $ingredients = [
            ['kode' => 'KA001', 'nama' => 'Kopi Arabika', 'stok' => 1000, 'satuan' => 'gram', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'GP001', 'nama' => 'Gula Pasir', 'stok' => 5000, 'satuan' => 'gram', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'SFC001', 'nama' => 'Susu Full Cream', 'stok' => 10000, 'satuan' => 'ml', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'CP001', 'nama' => 'Cup Plastik', 'stok' => 200, 'satuan' => 'pcs', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'SD001', 'nama' => 'Sedotan', 'stok' => 300, 'satuan' => 'pcs', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'TC001', 'nama' => 'Teh Celup', 'stok' => 100, 'satuan' => 'pcs', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'ES001', 'nama' => 'Es Batu', 'stok' => 5000, 'satuan' => 'gram', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'CK001', 'nama' => 'Coklat Bubuk', 'stok' => 2000, 'satuan' => 'gram', 'created_at' => now(), 'updated_at' => now()],
        ];

        Ingredient::insert($ingredients);
    }
}
