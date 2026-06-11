<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom username dan nama_pemilik
            $table->string('username')->unique()->after('email'); // Kolom username
            $table->string('nama_pemilik')->nullable()->after('username'); // Kolom nama pemilik
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom username dan nama_pemilik
            $table->dropColumn(['username', 'nama_pemilik']);
        });
    }
};
