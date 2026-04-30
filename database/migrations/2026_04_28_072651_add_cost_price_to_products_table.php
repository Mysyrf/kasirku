<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('avg_cost_price')->default(0); // harga modal rata-rata
            $table->integer('last_buy_price')->default(0); // harga beli terakhir
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['avg_cost_price', 'last_buy_price']);
        });
    }
};