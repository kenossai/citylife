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
        Schema::create('social_media_posts', function (Blueprint $table) {
            $table->id();

            // Content reference (polymorphic)
            $table->string('content_type'); // 'event', 'news', etc.
            $table->unsignedBigInteger('content_id');

            // Platform and post details
            $table->enum('platform', ['facebook', 'twitter', 'instagram', 'linkedin']);
            $table->text('content'); // The actual post content
            $table->enum('status', ['draft', 'scheduled', 'published', 'failed'])->default('draft');

            // Platform response data
            $table->string('platform_post_id')->nullable(); // ID from social media platform
            $table->json('response_data')->nullable(); // Full API response
            $table->text('error_message')->nullable(); // Error details if failed

            // Timing
            $table->timestamp('scheduled_at')->nullable(); // When to publish
            $table->timestamp('published_at')->nullable(); // When actually published

            $table->timestamps();

            // Indexes
            $table->index(['content_type', 'content_id']);
            $table->index(['platform', 'status']);
            $table->index(['status', 'scheduled_at']);
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_posts');
    }
};
