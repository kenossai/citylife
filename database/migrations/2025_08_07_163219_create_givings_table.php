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
        Schema::create('givings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('giving_type'); // 'tithe', 'offering', 'mission', 'building_fund', 'special'
            $table->text('description');
            $table->longText('content')->nullable();
            $table->string('payment_methods'); // 'online,bank_transfer,cash,cheque'
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('sort_code')->nullable();
            $table->string('online_giving_url')->nullable();
            $table->text('instructions')->nullable();
            $table->decimal('suggested_amount', 8, 2)->nullable();
            $table->string('featured_image')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('givings');
    }
};
