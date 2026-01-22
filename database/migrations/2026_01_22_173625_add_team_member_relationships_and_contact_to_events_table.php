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
        Schema::table('events', function (Blueprint $table) {
            // Add foreign key for event anchor/host (team member)
            $table->foreignId('event_anchor_id')->nullable()->after('location')->constrained('team_members')->nullOnDelete();

            // Add foreign key for contact person (church member)
            $table->foreignId('contact_person_id')->nullable()->after('event_anchor_id')->constrained('members')->nullOnDelete();

            // Add contact details
            $table->string('contact_email')->nullable()->after('contact_person_id');
            $table->string('contact_phone')->nullable()->after('contact_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['event_anchor_id']);
            $table->dropForeign(['contact_person_id']);
            $table->dropColumn(['event_anchor_id', 'contact_person_id', 'contact_email', 'contact_phone']);
        });
    }
};
