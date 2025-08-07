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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('content')->nullable();
            $table->string('instructor')->nullable();
            $table->string('category'); // e.g., 'Bible School Int\'l', 'Christian Development', etc.
            $table->integer('duration_weeks')->nullable();
            $table->string('schedule')->nullable(); // e.g., 'Sundays 9:00 AM'
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('location')->nullable();
            $table->string('featured_image')->nullable();
            $table->text('requirements')->nullable();
            $table->text('what_you_learn')->nullable();
            $table->text('course_objectives')->nullable();
            $table->integer('current_enrollments')->default(0);
            $table->boolean('has_certificate')->default(true); // Does course provide certificate?
            $table->integer('min_attendance_for_certificate')->default(5); // Minimum classes to attend for certificate
            $table->boolean('is_registration_open')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
