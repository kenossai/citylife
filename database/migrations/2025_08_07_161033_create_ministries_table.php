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
        Schema::create('ministries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('ministry_type'); // 'kids', 'youth', 'prayer', 'womens', 'mens', 'worship', 'other'
            $table->text('description');
            $table->longText('content')->nullable();
            $table->string('leader')->nullable();
            $table->string('assistant_leader')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('meeting_time')->nullable();
            $table->string('meeting_location')->nullable();
            $table->text('target_audience')->nullable(); // Age group or demographic
            $table->json('meeting_schedule')->nullable(); // Weekly schedule
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->text('requirements')->nullable(); // Any special requirements
            $table->text('how_to_join')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ministries');
    }
};
