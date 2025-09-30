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
        // Add SEO fields to events table
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->string('canonical_url')->nullable();
                $table->string('og_image')->nullable();
            });
        }

        // Add SEO fields to news table
        if (Schema::hasTable('news')) {
            Schema::table('news', function (Blueprint $table) {
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->string('canonical_url')->nullable();
                $table->string('og_image')->nullable();
            });
        }

        // Add SEO fields to teaching_series table
        if (Schema::hasTable('teaching_series')) {
            Schema::table('teaching_series', function (Blueprint $table) {
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->string('canonical_url')->nullable();
                $table->string('og_image')->nullable();
            });
        }

        // Add SEO fields to about_pages table
        if (Schema::hasTable('about_pages')) {
            Schema::table('about_pages', function (Blueprint $table) {
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->string('canonical_url')->nullable();
                $table->string('og_image')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove SEO fields from events table
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn(['meta_title', 'meta_description', 'meta_keywords', 'canonical_url', 'og_image']);
            });
        }

        // Remove SEO fields from news table
        if (Schema::hasTable('news')) {
            Schema::table('news', function (Blueprint $table) {
                $table->dropColumn(['meta_title', 'meta_description', 'meta_keywords', 'canonical_url', 'og_image']);
            });
        }

        // Remove SEO fields from teaching_series table
        if (Schema::hasTable('teaching_series')) {
            Schema::table('teaching_series', function (Blueprint $table) {
                $table->dropColumn(['meta_title', 'meta_description', 'meta_keywords', 'canonical_url', 'og_image']);
            });
        }

        // Remove SEO fields from about_pages table
        if (Schema::hasTable('about_pages')) {
            Schema::table('about_pages', function (Blueprint $table) {
                $table->dropColumn(['meta_title', 'meta_description', 'meta_keywords', 'canonical_url', 'og_image']);
            });
        }
    }
};
