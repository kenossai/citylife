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
            $table->string('youtube_live_url')->nullable()->after('video_url');
            $table->boolean('auto_fetch_live_stream')->default(false)->after('youtube_live_url');
            $table->timestamp('live_stream_checked_at')->nullable()->after('auto_fetch_live_stream');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teaching_series', function (Blueprint $table) {
            $table->dropColumn(['youtube_live_url', 'auto_fetch_live_stream', 'live_stream_checked_at']);
        });
    }
};
