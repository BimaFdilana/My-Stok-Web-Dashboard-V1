<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('ingredient_id')
                ->constrained('ingredients')
                ->onDelete('cascade');
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->onDelete('cascade');
            $table->integer('jumlah'); // Kolom jumlah stok
            $table->integer('jumlah_awal'); // Kolom jumlah stok awal
            $table->string('satuan');
            $table->date('tanggal'); // Kolom tanggal stok masuk
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }


    public function down()
    {
        Schema::dropIfExists('stocks');
    }
};