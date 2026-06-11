<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'username' => 'admin',
            'nama_pemilik' => 'Ahmad Rizky',
            'name' => 'Admin Toko',
            'email' => 'admin@mystock.com',
            'foto' => null,
            'role' => 'admin',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'username' => 'kasir1',
            'nama_pemilik' => 'Budi Santoso',
            'name' => 'Kasir 1',
            'email' => 'kasir1@mystock.com',
            'foto' => null,
            'role' => 'kasir',
            'password' => Hash::make('password123'),
        ]);
    }
}
