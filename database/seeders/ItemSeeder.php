<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $item1 = Item::create([
            'kode' => 'ITM001',
            'nama' => 'Kopi Susu',
            'harga' => 18000,
            'foto' => null,
            'kategori_id' => 1,
        ]);

        $item2 = Item::create([
            'kode' => 'ITM002',
            'nama' => 'Americano',
            'harga' => 15000,
            'foto' => null,
            'kategori_id' => 1,
        ]);

        $item3 = Item::create([
            'kode' => 'ITM003',
            'nama' => 'Es Teh Manis',
            'harga' => 8000,
            'foto' => null,
            'kategori_id' => 1,
        ]);

        $item4 = Item::create([
            'kode' => 'ITM004',
            'nama' => 'Coklat Susu',
            'harga' => 20000,
            'foto' => null,
            'kategori_id' => 1,
        ]);

        $item5 = Item::create([
            'kode' => 'ITM005',
            'nama' => 'Nasi Goreng',
            'harga' => 15000,
            'foto' => null,
            'kategori_id' => 2,
        ]);

        DB::table('item_ingredient')->insert([
            ['item_id' => $item1->id, 'ingredient_id' => 1, 'jumlah' => 20, 'satuan' => 'gram'],
            ['item_id' => $item1->id, 'ingredient_id' => 2, 'jumlah' => 15, 'satuan' => 'gram'],
            ['item_id' => $item1->id, 'ingredient_id' => 3, 'jumlah' => 100, 'satuan' => 'ml'],
            ['item_id' => $item1->id, 'ingredient_id' => 4, 'jumlah' => 1, 'satuan' => 'pcs'],
            ['item_id' => $item2->id, 'ingredient_id' => 1, 'jumlah' => 25, 'satuan' => 'gram'],
            ['item_id' => $item2->id, 'ingredient_id' => 7, 'jumlah' => 100, 'satuan' => 'gram'],
            ['item_id' => $item2->id, 'ingredient_id' => 4, 'jumlah' => 1, 'satuan' => 'pcs'],
            ['item_id' => $item3->id, 'ingredient_id' => 6, 'jumlah' => 1, 'satuan' => 'pcs'],
            ['item_id' => $item3->id, 'ingredient_id' => 2, 'jumlah' => 20, 'satuan' => 'gram'],
            ['item_id' => $item3->id, 'ingredient_id' => 7, 'jumlah' => 100, 'satuan' => 'gram'],
            ['item_id' => $item3->id, 'ingredient_id' => 4, 'jumlah' => 1, 'satuan' => 'pcs'],
            ['item_id' => $item4->id, 'ingredient_id' => 8, 'jumlah' => 30, 'satuan' => 'gram'],
            ['item_id' => $item4->id, 'ingredient_id' => 3, 'jumlah' => 150, 'satuan' => 'ml'],
            ['item_id' => $item4->id, 'ingredient_id' => 2, 'jumlah' => 20, 'satuan' => 'gram'],
            ['item_id' => $item4->id, 'ingredient_id' => 4, 'jumlah' => 1, 'satuan' => 'pcs'],
        ]);
    }
}
