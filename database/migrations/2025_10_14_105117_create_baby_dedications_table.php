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
        Schema::create('baby_dedications', function (Blueprint $table) {
            $table->id();

            // Baby Information
            $table->string('baby_first_name');
            $table->string('baby_middle_name')->nullable();
            $table->string('baby_last_name');
            $table->date('baby_date_of_birth');
            $table->enum('baby_gender', ['male', 'female']);
            $table->string('baby_place_of_birth')->nullable();
            $table->text('baby_special_notes')->nullable();

            // Parent/Guardian Information
            $table->string('father_first_name');
            $table->string('father_last_name');
            $table->string('father_email');
            $table->string('father_phone');
            $table->boolean('father_is_member')->default(false);
            $table->string('father_membership_number')->nullable();

            $table->string('mother_first_name');
            $table->string('mother_last_name');
            $table->string('mother_email');
            $table->string('mother_phone');
            $table->boolean('mother_is_member')->default(false);
            $table->string('mother_membership_number')->nullable();

            // Address Information
            $table->text('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('country')->default('United Kingdom');

            // Dedication Details
            $table->date('preferred_dedication_date')->nullable();
            $table->enum('preferred_service', ['morning', 'evening', 'either'])->default('either');
            $table->text('special_requests')->nullable();
            $table->boolean('photography_consent')->default(true);
            $table->boolean('video_consent')->default(true);

            // Church Information
            $table->boolean('regular_attendees')->default(false);
            $table->string('how_long_attending')->nullable();
            $table->string('previous_church')->nullable();
            $table->boolean('baptized_parents')->default(false);
            $table->text('faith_commitment')->nullable();

            // Emergency Contact
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_relationship');
            $table->string('emergency_contact_phone');

            // Admin fields
            $table->enum('status', ['pending', 'approved', 'scheduled', 'completed', 'cancelled'])->default('pending');
            $table->date('scheduled_date')->nullable();
            $table->string('scheduled_service')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            // GDPR and consent
            $table->boolean('gdpr_consent')->default(false);
            $table->timestamp('gdpr_consent_date')->nullable();
            $table->string('gdpr_consent_ip')->nullable();
            $table->boolean('newsletter_consent')->default(false);

            $table->timestamps();

            // Indexes
            $table->index(['status', 'scheduled_date']);
            $table->index(['baby_last_name', 'baby_first_name']);
            $table->index(['father_email', 'mother_email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baby_dedications');
    }
};
