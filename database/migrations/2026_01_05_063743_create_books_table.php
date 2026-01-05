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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            
            // Author relationship
            $table->foreignId('team_member_id')->nullable()->constrained()->onDelete('set null');
            
            // Basic book information
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('isbn')->nullable()->unique();
            $table->string('isbn13')->nullable()->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            
            // Publishing information
            $table->string('publisher')->nullable();
            $table->date('published_date')->nullable();
            $table->string('edition')->nullable();
            $table->string('language')->default('English');
            $table->integer('pages')->nullable();
            $table->enum('format', ['hardcover', 'paperback', 'ebook', 'audiobook'])->default('paperback');
            
            // Media
            $table->string('cover_image')->nullable();
            $table->string('back_cover_image')->nullable();
            $table->json('sample_pages')->nullable(); // Array of image URLs
            
            // Purchase and links
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency')->default('GBP');
            $table->string('purchase_link')->nullable();
            $table->string('amazon_link')->nullable();
            $table->string('preview_link')->nullable();
            
            // Categories and tags
            $table->string('category')->nullable(); // Theology, Biography, Devotional, etc.
            $table->json('tags')->nullable();
            $table->json('topics')->nullable(); // Array of topics covered
            
            // Status and display
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            
            // SEO
            $table->string('slug')->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            
            // Stats
            $table->integer('views_count')->default(0);
            $table->decimal('rating', 3, 2)->nullable(); // Average rating
            $table->integer('reviews_count')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active', 'is_featured']);
            $table->index(['team_member_id', 'is_active']);
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
