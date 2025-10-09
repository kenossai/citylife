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
        Schema::create('cafe_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('member_id')->nullable()->constrained('members')->onDelete('set null');
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->enum('order_type', ['dine_in', 'takeaway', 'online'])->default('dine_in');
            $table->string('table_number')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['cash', 'card', 'bank_transfer', 'online'])->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'refunded', 'failed'])->default('pending');
            $table->enum('order_status', ['pending', 'confirmed', 'preparing', 'ready', 'served', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->text('special_instructions')->nullable();
            $table->timestamp('order_date');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cafe_orders');
    }
};
