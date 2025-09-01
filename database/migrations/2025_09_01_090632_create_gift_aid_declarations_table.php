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
        Schema::create('gift_aid_declarations', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->text('address');
            $table->string('postcode', 10);
            $table->string('phone', 20);
            $table->string('email');
            $table->string('gift_aid_code', 50)->unique();
            $table->date('confirmation_date');
            $table->boolean('confirm_declaration')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['email', 'is_active']);
            $table->index('gift_aid_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_aid_declarations');
    }
};
