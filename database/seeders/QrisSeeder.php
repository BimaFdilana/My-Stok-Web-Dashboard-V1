<?php

namespace Database\Seeders;

use App\Models\QrisSetting;
use Illuminate\Database\Seeder;

class QrisSeeder extends Seeder
{
    public function run()
    {
        QrisSetting::create([
            'foto' => 'qris/dummy-qr.png',
            'nama_merchant' => 'MyStock Coffee',
            'keterangan' => 'Scan untuk pembayaran QRIS',
            'is_active' => true,
        ]);
    }
}
