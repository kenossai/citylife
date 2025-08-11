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
        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->boolean('gdpr_consent')->default(false);
            $table->string('ip_address')->nullable();
            $table->enum('status', ['new', 'in_progress', 'responded', 'archived'])->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->unsignedBigInteger('responded_by')->nullable();
            $table->timestamps();

            $table->foreign('responded_by')->references('id')->on('users');
            $table->index(['status', 'created_at']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
    }
};
