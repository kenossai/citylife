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
        Schema::create('becoming_sections', function (Blueprint $table) {
            $table->id();
            $table->string('tagline')->default('Are You Ready to Make a Difference?');
            $table->string('title')->default('Inspiring and Helping for Better');
            $table->string('title_highlight')->default('Lifestyle');
            $table->text('description');
            $table->string('volunteer_title')->default('Become A Volunteer');
            $table->string('volunteer_icon')->default('icon-unity');
            $table->string('new_member_title')->default("I'm New Here");
            $table->string('new_member_icon')->default('icon-healthcare');
            $table->string('background_image')->nullable();
            $table->string('left_image')->nullable();
            $table->string('right_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('becoming_sections');
    }
};
