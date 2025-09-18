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
        Schema::table('members', function (Blueprint $table) {
            $table->boolean('gdpr_consent')->default(false)->after('receives_sms');
            $table->timestamp('gdpr_consent_date')->nullable()->after('gdpr_consent');
            $table->string('gdpr_consent_ip')->nullable()->after('gdpr_consent_date');
            $table->boolean('newsletter_consent')->default(false)->after('gdpr_consent_ip');
            $table->timestamp('newsletter_consent_date')->nullable()->after('newsletter_consent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn([
                'gdpr_consent',
                'gdpr_consent_date',
                'gdpr_consent_ip',
                'newsletter_consent',
                'newsletter_consent_date'
            ]);
        });
    }
};
