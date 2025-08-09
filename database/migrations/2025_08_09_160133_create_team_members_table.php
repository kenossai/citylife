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
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name')->virtualAs('CONCAT(first_name, " ", last_name)');
            $table->string('title')->nullable(); // Dr., Rev., Pastor, etc.
            $table->string('position'); // Senior Pastor, Assistant Pastor, Worship Leader, etc.
            $table->enum('team_type', ['pastoral', 'leadership']); // Which team they belong to

            // Contact Information
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            // Bio and Description
            $table->text('bio')->nullable(); // Full biography
            $table->text('short_description')->nullable(); // Brief summary
            $table->text('ministry_focus')->nullable(); // What they focus on in ministry
            $table->json('responsibilities')->nullable(); // Array of responsibilities

            // Personal Information
            $table->string('spouse_name')->nullable();
            $table->year('joined_church')->nullable(); // When they joined the church
            $table->year('started_ministry')->nullable(); // When they started ministry

            // Media
            $table->string('profile_image')->nullable();
            $table->string('featured_image')->nullable(); // For banners/headers
            
            // Books and Publications
            $table->json('books_written')->nullable(); // Array of book objects with title, cover_image, links, etc.
            $table->json('courses_taught')->nullable(); // Courses they teach

            // Ministry Details
            $table->json('ministry_areas')->nullable(); // Areas they serve in
            $table->text('calling_testimony')->nullable(); // Their calling story
            $table->text('achievements')->nullable(); // Notable achievements

            // Display Settings
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false); // Featured on homepage
            $table->boolean('show_contact_info')->default(false); // Whether to show contact publicly

            // SEO
            $table->string('slug')->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['team_type', 'sort_order']);
            $table->index(['is_active', 'team_type']);
            $table->index(['is_featured', 'team_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
