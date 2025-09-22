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
        Schema::create('pastoral_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->enum('reminder_type', [
                'birthday',
                'wedding_anniversary',
                'baptism_anniversary',
                'membership_anniversary',
                'salvation_anniversary',
                'custom'
            ]);
            $table->string('title')->nullable(); // For custom reminders
            $table->text('description')->nullable();
            $table->date('reminder_date'); // The actual anniversary/birthday date
            $table->integer('days_before_reminder')->default(7); // How many days before to send reminder
            $table->boolean('is_annual')->default(true); // Repeats yearly
            $table->boolean('is_active')->default(true);
            $table->json('notification_recipients')->nullable(); // Staff members to notify
            $table->json('custom_message')->nullable(); // Custom message templates
            $table->timestamp('last_sent_at')->nullable();
            $table->year('year_created')->nullable(); // For anniversaries that started in a specific year
            $table->timestamps();

            // Indexes
            $table->index(['reminder_date', 'is_active']);
            $table->index(['member_id', 'reminder_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pastoral_reminders');
    }
};
