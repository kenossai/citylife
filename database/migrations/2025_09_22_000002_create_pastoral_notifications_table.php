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
        Schema::create('pastoral_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pastoral_reminder_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('notification_type'); // email, dashboard, sms, etc.
            $table->string('recipient_email')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->enum('status', ['pending', 'sent', 'failed', 'cancelled'])->default('pending');
            $table->timestamp('scheduled_for');
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->json('metadata')->nullable(); // Additional data like years married, age, etc.
            $table->timestamps();

            // Indexes
            $table->index(['scheduled_for', 'status']);
            $table->index(['member_id', 'notification_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pastoral_notifications');
    }
};
