<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // ID unik
            $table->string('nama'); // Nama kategori
            $table->timestamps();
        });

        // Relasi tabel ingredients ke categories
        Schema::table('ingredients', function (Blueprint $table) {
            $table->foreignId('kategori_id')->nullable()->constrained('categories')->onDelete('set null');
        });

        // Relasi tabel items ke categories
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('kategori_id')->nullable()->constrained('categories')->onDelete('set null');
        });
    }

    public function down()
    {
        // Hapus relasi dari tabel items
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
        });

        // Hapus relasi dari tabel ingredients
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
        });

        Schema::dropIfExists('categories');
    }
};
