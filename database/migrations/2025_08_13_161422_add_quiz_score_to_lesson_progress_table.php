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
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->decimal('quiz_score', 5, 2)->nullable()->after('attempts'); // Quiz score out of 100
            $table->integer('time_spent_minutes')->default(0)->after('quiz_score'); // Time spent on lesson
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->dropColumn(['quiz_score', 'time_spent_minutes']);
        });
    }
};
