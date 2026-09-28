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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Relationship
            $table->foreignId('category_id')->constrained('categories')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('type_id')->constrained('types')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('color_id')->constrained('colors')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('unit_id')->constrained('units')->cascadeOnUpdate()->restrictOnDelete();
            // Product Information
            $table->string('product_code', 50)->unique();
            $table->string('product_name', 100);
            // Price & Stock
            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->integer('minimum_stock')->default(0);
            // Status
            $table->string('status', 50)->default('active');
            // User Tracking
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
