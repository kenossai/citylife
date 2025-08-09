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
        Schema::create('about_page', function (Blueprint $table) {
            $table->id();

            // Main about section
            $table->string('title')->default('About Us');
            $table->text('introduction'); // Main introduction paragraph
            $table->string('featured_image')->nullable(); // Hero/banner image

            // Church details
            $table->string('church_name')->default('City Life');
            $table->text('church_description')->nullable(); // Brief church description
            $table->string('affiliation')->nullable(); // e.g., "Assemblies of God"
            $table->string('location_description')->nullable(); // e.g., "heart of Kelham Island"

            // Meta information
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable(); // Array of keywords

            // Social media links
            $table->json('social_media_links')->nullable(); // Array of social media URLs

            // Contact information
            $table->string('phone_number')->nullable();
            $table->string('email_address')->nullable();
            $table->text('address')->nullable();

            // SEO and display options
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('slug')->default('about-us');

            $table->timestamps();

            // Add indexes
            $table->index(['is_active', 'sort_order']);
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_page');
    }
};
