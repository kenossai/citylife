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
        Schema::create('volunteer_applications', function (Blueprint $table) {
            $table->id();
            
            // Application Details
            $table->enum('application_type', ['event_only', 'ongoing']);
            $table->string('team');
            
            // Personal Details
            $table->string('name');
            $table->date('date_of_birth');
            $table->enum('sex', ['male', 'female', 'prefer_not_to_say'])->nullable();
            $table->string('email');
            $table->string('mobile');
            $table->text('address');
            
            // Medical & First Aid
            $table->boolean('medical_professional');
            $table->boolean('first_aid_certificate');
            
            // Background Information
            $table->text('church_background');
            $table->text('employment_details');
            $table->text('support_mission');
            
            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            
            // Declaration
            $table->boolean('eligible_to_work');
            
            // Data Protection
            $table->boolean('data_processing_consent');
            $table->boolean('data_protection_consent');
            
            // Application Status
            $table->enum('status', ['pending', 'approved', 'rejected', 'under_review'])->default('pending');
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_applications');
    }
};
