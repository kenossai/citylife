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
        Schema::table('cafe_orders', function (Blueprint $table) {
            $table->timestamp('scheduled_for')->nullable()->after('special_instructions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cafe_orders', function (Blueprint $table) {
            $table->dropColumn('scheduled_for');
        });
    }
};
