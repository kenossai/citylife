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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();

            // Media
            $table->string('background_image');
            $table->string('background_overlay_color')->default('#000000');
            $table->integer('background_overlay_opacity')->default(50); // 0-100

            // Button/CTA
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->boolean('button_opens_new_tab')->default(false);

            // Display Settings
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);

            // Content Layout
            $table->enum('text_alignment', ['left', 'center', 'right'])->default('left');
            $table->enum('text_color', ['light', 'dark'])->default('light');

            // Animation Settings
            $table->enum('animation_type', ['fadeIn', 'slideInLeft', 'slideInRight', 'slideInUp', 'slideInDown'])->default('fadeIn');
            $table->integer('animation_duration')->default(1000); // milliseconds

            // SEO
            $table->string('slug')->unique();
            $table->string('alt_text')->nullable(); // for background image

            $table->timestamps();

            // Indexes
            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
