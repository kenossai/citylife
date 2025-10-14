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
        Schema::table('teaching_series', function (Blueprint $table) {
            $table->longText('sermon_notes_content')->nullable()->after('sermon_notes');
            $table->string('sermon_notes_content_type')->default('rich_text')->after('sermon_notes_content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teaching_series', function (Blueprint $table) {
            $table->dropColumn(['sermon_notes_content', 'sermon_notes_content_type']);
        });
    }
};
