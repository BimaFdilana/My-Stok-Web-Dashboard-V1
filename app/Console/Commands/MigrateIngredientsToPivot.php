<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use App\Models\Ingredient;
use Illuminate\Support\Facades\DB;

class MigrateIngredientsToPivot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-ingredients-to-pivot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate ingredients to the pivot table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $items = Item::all(); // Ambil semua item
        $ingredients = Ingredient::all(); // Ambil semua ingredients

        foreach ($items as $item) {
            foreach ($ingredients as $ingredient) {
                DB::table('item_ingredient')->insert([
                    'item_id' => $item->id,
                    'ingredient_id' => $ingredient->id,
                    'jumlah' => 1, // Default jumlah
                    'satuan' => $ingredient->satuan, // Ambil satuan dari ingredients
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->info('Data ingredients berhasil dipindahkan ke tabel pivot!');
    }
}
