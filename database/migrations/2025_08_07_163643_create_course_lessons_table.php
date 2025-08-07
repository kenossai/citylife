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
        Schema::create('course_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->integer('lesson_number'); // Order of lesson in course (1-6 for 6 weeks)
            $table->integer('duration_minutes')->nullable();
            $table->text('homework')->nullable();
            $table->text('quiz_questions')->nullable(); // JSON format
            $table->boolean('is_published')->default(false);
            $table->date('available_date')->nullable(); // When lesson becomes available
            $table->timestamps();
            
            $table->unique(['course_id', 'lesson_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_lessons');
    }
};
