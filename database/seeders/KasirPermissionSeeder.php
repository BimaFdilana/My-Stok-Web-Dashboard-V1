<?php

namespace Database\Seeders;

use App\Models\KasirPermission;
use App\Models\User;
use Illuminate\Database\Seeder;

class KasirPermissionSeeder extends Seeder
{
    public function run()
    {
        $kasirs = User::where('role', 'kasir')->get();

        foreach ($kasirs as $kasir) {
            KasirPermission::firstOrCreate(['user_id' => $kasir->id, 'menu_key' => 'dashboard']);
            KasirPermission::firstOrCreate(['user_id' => $kasir->id, 'menu_key' => 'kasir']);
        }
    }
}
