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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'admin', 'pastor', 'finance_manager', 'volunteer_coordinator'
            $table->string('display_name'); // e.g., 'Administrator', 'Pastor', 'Finance Manager'
            $table->text('description')->nullable();
            $table->string('color')->nullable(); // For UI display
            $table->integer('priority')->default(0); // Higher priority roles have more access
            $table->boolean('is_system_role')->default(false); // System roles that can't be deleted
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // Role-specific settings
            $table->timestamps();

            $table->index(['is_active', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
