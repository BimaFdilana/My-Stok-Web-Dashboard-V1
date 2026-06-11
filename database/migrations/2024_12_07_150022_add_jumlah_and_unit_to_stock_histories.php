<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Kosongkan karena kolom jumlah dan unit sudah ada di migrasi awal
    }

    public function down()
    {
        // Kosongkan karena tidak perlu rollback
    }
};
