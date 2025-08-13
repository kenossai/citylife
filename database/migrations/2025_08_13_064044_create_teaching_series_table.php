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
        Schema::create('teaching_series', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('summary')->nullable();
            $table->string('image')->nullable();
            $table->string('video_url')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('pastor')->nullable();
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            $table->date('series_date')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->text('scripture_references')->nullable();
            $table->integer('views_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['is_published', 'is_featured']);
            $table->index(['category']);
            $table->index(['series_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teaching_series');
    }
};
