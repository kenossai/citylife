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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'view_members', 'edit_courses', 'manage_finances'
            $table->string('display_name'); // e.g., 'View Members', 'Edit Courses', 'Manage Finances'
            $table->string('category'); // e.g., 'members', 'courses', 'finance', 'system'
            $table->text('description')->nullable();
            $table->boolean('is_system_permission')->default(false); // System permissions that can't be deleted
            $table->json('metadata')->nullable(); // Additional permission metadata
            $table->timestamps();

            $table->index(['category', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
