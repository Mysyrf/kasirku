<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('price_retail')->default(0)->after('price'); // harga eceran
            $table->integer('price_wholesale')->default(0)->after('price_retail'); // harga grosir
            $table->integer('wholesale_min_qty')->default(10)->after('price_wholesale'); // minimal beli grosir
            $table->integer('discount_percent')->default(0)->after('wholesale_min_qty'); // diskon khusus produk
            $table->enum('price_type', ['single', 'retail_wholesale'])->default('single')->after('discount_percent');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'price_retail', 'price_wholesale', 
                'wholesale_min_qty', 'discount_percent', 'price_type'
            ]);
        });
    }
};