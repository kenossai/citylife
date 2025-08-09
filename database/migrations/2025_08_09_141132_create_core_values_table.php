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
        Schema::create('core_values', function (Blueprint $table) {
            $table->id();

            // Core value details
            $table->string('title'); // e.g., "Care", "Communication", etc.
            $table->string('slug'); // URL-friendly version of title
            $table->text('description'); // Main content/description
            $table->text('short_description')->nullable(); // Brief summary

            // Bible verse information
            $table->text('bible_verse')->nullable(); // The actual verse text
            $table->string('bible_reference')->nullable(); // e.g., "Hebrews 6:10-12"

            // Visual elements
            $table->string('icon')->nullable(); // Icon class or image path
            $table->string('featured_image')->nullable(); // Optional image
            $table->string('background_color')->nullable(); // Hex color code
            $table->string('text_color')->nullable(); // Hex color code

            // Organization
            $table->integer('sort_order')->default(0); // Display order
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false); // Highlight important values

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // Relationship to about page
            $table->foreignId('about_page_id')->nullable()->constrained('about_page')->onDelete('cascade');

            $table->timestamps();

            // Add indexes
            $table->index(['is_active', 'sort_order']);
            $table->index(['about_page_id', 'sort_order']);
            $table->unique(['slug', 'about_page_id']); // Unique slug per about page
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_values');
    }
};
