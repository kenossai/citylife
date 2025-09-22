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
        Schema::table('pastoral_reminders', function (Blueprint $table) {
            $table->boolean('send_to_member')->default(false)->after('is_active');
            $table->string('member_notification_type')->default('email')->after('send_to_member'); // email, sms, both
            $table->json('member_message_template')->nullable()->after('member_notification_type');
            $table->integer('days_before_member_notification')->default(0)->after('member_message_template'); // 0 = on the day
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pastoral_reminders', function (Blueprint $table) {
            $table->dropColumn([
                'send_to_member',
                'member_notification_type',
                'member_message_template',
                'days_before_member_notification'
            ]);
        });
    }
};
