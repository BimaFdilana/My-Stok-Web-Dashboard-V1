<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropColumn(['item_id', 'quantity']);
            
            $table->foreignId('user_id')->constrained('users')->after('id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('total', 15, 2)->after('user_id');
            $table->dropColumn('total_harga');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'total']);
            
            $table->foreignId('item_id')->constrained('items');
            $table->integer('quantity');
            $table->decimal('total_harga', 15, 2);
        });
    }
}; 