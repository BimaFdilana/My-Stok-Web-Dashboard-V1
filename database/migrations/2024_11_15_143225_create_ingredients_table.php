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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('item_id') // Relasi ke tabel items
                ->constrained('items')
                ->onDelete('cascade'); // Hapus ingredients jika item terkait dihapus
            $table->string('kode')->unique(); // Kode unik untuk setiap bahan baku
            $table->string('nama'); // Nama bahan baku
            $table->integer('stok'); // Stok bahan baku
            $table->string('satuan'); // Satuan bahan baku (gram, ml, pcs)
            $table->timestamps(); // Timestamps (created_at, updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients'); // Hapus tabel jika rollback
    }
};
