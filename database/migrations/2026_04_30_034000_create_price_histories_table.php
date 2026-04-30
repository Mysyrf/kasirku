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
        Schema::create('price_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('old_retail_price')->default(0);
            $table->integer('new_retail_price')->default(0);
            $table->integer('old_wholesale_price')->default(0);
            $table->integer('new_wholesale_price')->default(0);
            $table->integer('old_discount_percent')->default(0);
            $table->integer('new_discount_percent')->default(0);
            $table->string('changed_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_histories');
    }
};
