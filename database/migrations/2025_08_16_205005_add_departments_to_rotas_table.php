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
        Schema::table('rotas', function (Blueprint $table) {
            $table->json('departments')->after('department_type')->nullable();
            $table->text('description')->after('title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rotas', function (Blueprint $table) {
            $table->dropColumn(['departments', 'description']);
        });
    }
};
