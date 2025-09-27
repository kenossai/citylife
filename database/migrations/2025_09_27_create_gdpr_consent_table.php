<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gdpr_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('consent_type'); // marketing, data_processing, communication, etc.
            $table->boolean('consent_given')->default(false);
            $table->timestamp('consent_date')->nullable();
            $table->timestamp('consent_withdrawn_date')->nullable();
            $table->string('consent_method')->nullable(); // web_form, email, phone, in_person
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->json('consent_details')->nullable(); // Additional consent details
            $table->text('withdrawal_reason')->nullable();
            $table->timestamps();

            $table->index(['member_id', 'consent_type']);
            $table->index('consent_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gdpr_consents');
    }
};
