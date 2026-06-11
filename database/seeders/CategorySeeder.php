<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['nama' => 'Minuman', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Makanan', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Wadah', 'created_at' => now(), 'updated_at' => now()],
        ];

        Category::insert($categories);
    }
}
