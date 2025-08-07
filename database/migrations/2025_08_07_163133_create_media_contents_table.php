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
        Schema::create('media_contents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('media_type'); // 'teaching_series', 'talktimes', 'music', 'video', 'audio'
            $table->string('series_name')->nullable(); // For grouping related content
            $table->string('speaker_artist')->nullable();
            $table->date('release_date')->nullable();
            $table->string('video_url')->nullable(); // YouTube, Vimeo, etc.
            $table->string('audio_url')->nullable(); // Audio file or streaming URL
            $table->string('download_url')->nullable(); // For downloadable content
            $table->string('thumbnail')->nullable();
            $table->integer('duration')->nullable(); // Duration in minutes
            $table->text('scripture_reference')->nullable();
            $table->json('tags')->nullable(); // For categorization
            $table->integer('views_count')->default(0);
            $table->integer('downloads_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_contents');
    }
};
