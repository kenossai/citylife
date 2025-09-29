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
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id();

            // User information
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('user_name')->nullable(); // Store name in case user is deleted
            $table->string('user_email')->nullable(); // Store email in case user is deleted

            // Action details
            $table->string('action'); // create, update, delete, view, export, etc.
            $table->string('resource_type'); // Model class name
            $table->string('resource_id')->nullable(); // ID of the affected resource
            $table->string('resource_name')->nullable(); // Name/title of the resource

            // Request details
            $table->string('ip_address', 45);
            $table->string('user_agent', 500)->nullable();
            $table->string('url', 500)->nullable();
            $table->string('method', 10)->nullable(); // GET, POST, PUT, DELETE

            // Data changes
            $table->json('old_values')->nullable(); // Previous data
            $table->json('new_values')->nullable(); // New data
            $table->json('attributes')->nullable(); // Additional metadata

            // Categorization
            $table->string('category')->default('general'); // general, sensitive, financial, personal, etc.
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->boolean('is_sensitive')->default(false);

            // Additional context
            $table->text('description')->nullable();
            $table->json('context')->nullable(); // Additional context data

            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'created_at']);
            $table->index(['resource_type', 'resource_id']);
            $table->index(['action', 'created_at']);
            $table->index(['category', 'created_at']);
            $table->index(['is_sensitive', 'created_at']);
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};
