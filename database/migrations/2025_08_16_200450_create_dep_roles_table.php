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
        Schema::create('dep_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Lead Vocalist', 'Sound Engineer', 'Lead Pastor'
            $table->string('slug')->unique();
            $table->enum('department_type', ['worship', 'technical', 'preacher']); // Which department this role belongs to
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index for better performance
            $table->index(['department_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dep_roles');
    }
};
