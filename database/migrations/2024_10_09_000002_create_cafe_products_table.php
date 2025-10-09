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
        Schema::create('cafe_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('cafe_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('ingredients')->nullable();
            $table->decimal('price', 8, 2);
            $table->decimal('cost_price', 8, 2)->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable(); // Multiple images
            $table->integer('stock_quantity')->nullable();
            $table->boolean('track_stock')->default(false);
            $table->enum('size', ['small', 'medium', 'large'])->nullable();
            $table->json('dietary_info')->nullable(); // vegan, gluten-free, etc.
            $table->json('nutritional_info')->nullable(); // calories, allergens, etc.
            $table->integer('preparation_time')->nullable(); // in minutes
            $table->enum('temperature', ['hot', 'cold', 'room_temp'])->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cafe_products');
    }
};
