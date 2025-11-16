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
        // Drop cafe tables in correct order (considering foreign key constraints)
        Schema::dropIfExists('cafe_order_items');
        Schema::dropIfExists('cafe_orders');
        Schema::dropIfExists('cafe_products');
        Schema::dropIfExists('cafe_categories');
        Schema::dropIfExists('cafe_settings');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This is a destructive migration
        // We don't recreate the tables in down() method
        // as they are being permanently removed
    }
};
