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
        Schema::table('cafe_settings', function (Blueprint $table) {
            $table->string('name')->after('key');
            $table->boolean('is_public')->default(false)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cafe_settings', function (Blueprint $table) {
            $table->dropColumn(['name', 'is_public']);
        });
    }
};
