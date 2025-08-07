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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('membership_number')->unique(); // Auto-generated membership ID
            $table->string('title')->nullable(); // Mr, Mrs, Miss, Dr, Rev, etc.
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('preferred_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('marital_status')->nullable(); // Single, Married, Divorced, Widowed
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('alternative_phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('United Kingdom');
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('occupation')->nullable();
            $table->string('employer')->nullable();
            $table->enum('membership_status', ['visitor', 'regular_attendee', 'member', 'inactive', 'transferred'])->default('visitor');
            $table->date('first_visit_date')->nullable();
            $table->date('membership_date')->nullable();
            $table->string('baptism_status')->nullable(); // Not baptized, Baptized, Confirmed
            $table->date('baptism_date')->nullable();
            $table->string('previous_church')->nullable();
            $table->json('ministries_involved')->nullable(); // Array of ministry IDs
            $table->json('skills_talents')->nullable(); // Array of skills/talents
            $table->text('prayer_requests')->nullable();
            $table->text('special_needs')->nullable();
            $table->boolean('receives_newsletter')->default(true);
            $table->boolean('receives_sms')->default(false);
            $table->string('photo')->nullable();
            $table->text('notes')->nullable(); // Admin notes
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
