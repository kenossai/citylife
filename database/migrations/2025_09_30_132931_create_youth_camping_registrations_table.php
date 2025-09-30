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
        Schema::create('youth_camping_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('youth_camping_id')->constrained()->onDelete('cascade');

            // Child Information
            $table->string('child_first_name');
            $table->string('child_last_name');
            $table->date('child_date_of_birth');
            $table->integer('child_age')->nullable();
            $table->enum('child_gender', ['male', 'female'])->nullable();
            $table->string('child_grade_school')->nullable();
            $table->enum('child_t_shirt_size', ['XS', 'S', 'M', 'L', 'XL'])->nullable();

            // Parent/Guardian Information (who is registering)
            $table->string('parent_first_name');
            $table->string('parent_last_name');
            $table->string('parent_email');
            $table->string('parent_phone');
            $table->enum('parent_relationship', ['mother', 'father', 'guardian', 'other'])->default('mother');

            // Contact & Address Information
            $table->string('home_address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('home_phone')->nullable();
            $table->string('work_phone')->nullable();

            // Emergency Contact
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->string('emergency_contact_relationship');

            // Medical Information
            $table->json('medical_conditions')->nullable();
            $table->json('medications')->nullable();
            $table->json('allergies')->nullable();
            $table->json('dietary_requirements')->nullable();
            $table->enum('swimming_ability', ['non_swimmer', 'beginner', 'intermediate', 'advanced'])->nullable();
            $table->string('doctor_name')->nullable();
            $table->string('doctor_phone')->nullable();
            $table->string('health_card_number')->nullable();

            // Consent & Permissions
            $table->boolean('consent_photo_video')->default(false);
            $table->boolean('consent_medical_treatment')->default(false);
            $table->boolean('consent_activities')->default(false);
            $table->boolean('consent_pickup_authorized_persons')->default(false);
            $table->json('pickup_authorized_persons')->nullable(); // People authorized to pick up child

            // Registration Management
            $table->text('special_needs')->nullable();
            $table->text('additional_notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'waitlist'])->default('pending');
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'refunded'])->default('pending');
            $table->decimal('payment_amount', 8, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamp('registration_date')->nullable();
            $table->timestamp('confirmation_sent_at')->nullable();

            $table->timestamps();

            // Indexes for better performance
            $table->index('youth_camping_id', 'ycr_camping_id_idx');
            $table->index('status', 'ycr_status_idx');
            $table->index('payment_status', 'ycr_payment_status_idx');
            $table->index('registration_date', 'ycr_registration_date_idx');
            $table->index(['child_last_name', 'child_first_name'], 'ycr_child_name_idx');
            $table->index('parent_email', 'ycr_parent_email_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youth_camping_registrations');
    }
};
