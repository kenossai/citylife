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
        Schema::create('worship_department_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worship_department_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('role')->nullable(); // e.g., 'Lead Vocalist', 'Guitarist', 'Drummer', 'Dancer'
            $table->text('skills')->nullable(); // JSON or text field for skills
            $table->text('worship_bio')->nullable(); // Worship background/experience
            $table->date('joined_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_head')->default(false); // if this person is head of department
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Ensure a member can only be in each department once
            $table->unique(['worship_department_id', 'member_id'], 'worship_dept_member_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worship_department_members');
    }
};
