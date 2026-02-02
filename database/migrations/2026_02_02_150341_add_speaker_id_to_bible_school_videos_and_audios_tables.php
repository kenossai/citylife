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
        Schema::table('bible_school_videos', function (Blueprint $table) {
            $table->foreignId('bible_school_speaker_id')->nullable()->after('bible_school_event_id')->constrained()->nullOnDelete();
        });

        Schema::table('bible_school_audios', function (Blueprint $table) {
            $table->foreignId('bible_school_speaker_id')->nullable()->after('bible_school_event_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bible_school_videos', function (Blueprint $table) {
            $table->dropForeign(['bible_school_speaker_id']);
            $table->dropColumn('bible_school_speaker_id');
        });

        Schema::table('bible_school_audios', function (Blueprint $table) {
            $table->dropForeign(['bible_school_speaker_id']);
            $table->dropColumn('bible_school_speaker_id');
        });
    }
};
