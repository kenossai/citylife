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
            $table->string('event_anchor')->nullable()->after('location');
            $table->string('guest_speaker')->nullable()->after('event_anchor');
            $table->boolean('requires_registration')->default(false)->after('guest_speaker');
            $table->text('registration_details')->nullable()->after('requires_registration');
            $table->integer('max_attendees')->nullable()->after('registration_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'event_anchor',
                'guest_speaker', 
                'requires_registration',
                'registration_details',
                'max_attendees'
            ]);
        });
    }
};
