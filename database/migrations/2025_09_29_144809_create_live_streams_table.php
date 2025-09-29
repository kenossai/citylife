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
        Schema::create('live_streams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('platform')->default('youtube'); // youtube, vimeo, facebook, custom
            $table->string('stream_url');
            $table->string('embed_code')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->dateTime('scheduled_start');
            $table->dateTime('scheduled_end');
            $table->dateTime('actual_start')->nullable();
            $table->dateTime('actual_end')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, live, ended, cancelled
            $table->integer('estimated_viewers')->default(0);
            $table->integer('peak_viewers')->default(0);
            $table->json('stream_settings')->nullable(); // quality, chat settings, etc.
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_public')->default(true);
            $table->boolean('enable_chat')->default(true);
            $table->boolean('auto_record')->default(true);
            $table->string('recording_url')->nullable();
            $table->text('pastor_notes')->nullable();
            $table->json('tags')->nullable();
            $table->string('category')->default('service'); // service, event, prayer, youth, etc.
            $table->timestamps();

            $table->index(['status', 'scheduled_start']);
            $table->index(['is_featured', 'is_public']);
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_streams');
    }
};
