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
        Schema::create('s_e_o_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('City Life International Church');
            $table->text('site_description')->nullable();
            $table->text('default_keywords')->nullable();
            $table->string('google_analytics_id')->nullable();
            $table->string('google_search_console_id')->nullable();
            $table->string('facebook_app_id')->nullable();
            $table->string('twitter_handle')->nullable();
            $table->string('default_og_image')->nullable();
            $table->text('robots_txt_custom')->nullable();
            $table->json('schema_organization')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_e_o_settings');
    }
};
