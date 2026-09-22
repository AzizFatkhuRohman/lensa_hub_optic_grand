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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->string('so_number')->unique();
            $table->date('so_date');
            $table->string('description');
            $table->enum('payment_method',['cash','kredit','transfer'])->default('cash');
            $table->date('delivery_date');
            $table->enum('status',['draft','confirm','delivered'])->default('draft');
            $table->decimal('total_tax');
            $table->decimal('total_price');
            $table->decimal('total_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
