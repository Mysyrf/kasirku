<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('customer_name')->nullable();
            $table->enum('type', ['retail', 'wholesale'])->default('retail');
            $table->integer('subtotal');
            $table->integer('discount')->default(0);
            $table->integer('total');
            $table->integer('paid');
            $table->integer('change');
            $table->string('cashier')->default('Admin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};