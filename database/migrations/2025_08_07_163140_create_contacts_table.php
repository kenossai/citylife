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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('church_name')->default('City Life International Church');
            $table->text('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('country');
            $table->string('phone');
            $table->string('email');
            $table->string('website_url')->nullable();
            $table->text('office_hours')->nullable();
            $table->json('service_times'); // Array of service times
            $table->string('map_embed_url')->nullable(); // Google Maps embed URL
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->text('directions')->nullable();
            $table->text('parking_info')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
