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
        Schema::dropIfExists('skill_and_roles');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('skill_and_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['skill', 'role']);
            $table->enum('category', ['worship', 'technical', 'preacher', 'general']);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['type', 'category', 'is_active']);
        });
    }
};
