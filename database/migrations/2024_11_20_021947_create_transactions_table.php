<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); // Foreign key untuk tabel items
            $table->integer('quantity'); // Jumlah barang yang dibeli
            $table->decimal('total_harga', 15, 2); // Total harga transaksi (format desimal)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}