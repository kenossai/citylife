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
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->datetime('assigned_at')->default(now());
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('expires_at')->nullable(); // Optional expiration for temporary roles
            $table->boolean('is_active')->default(true);
            $table->json('conditions')->nullable(); // Optional conditions for role assignment
            $table->timestamps();

            $table->unique(['user_id', 'role_id']);
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
