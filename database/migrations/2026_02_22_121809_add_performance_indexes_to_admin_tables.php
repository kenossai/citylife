<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // members: filtered by is_active and created_at in every dashboard widget
        Schema::table('members', function (Blueprint $table) {
            $table->index('is_active', 'idx_members_is_active');
            $table->index('created_at', 'idx_members_created_at');
            $table->index('date_of_birth', 'idx_members_date_of_birth');
            $table->index('newsletter_consent', 'idx_members_newsletter_consent');
        });

        // course_enrollments: filtered by status, enrollment_date, progress_percentage
        Schema::table('course_enrollments', function (Blueprint $table) {
            $table->index('status', 'idx_enrollments_status');
            $table->index('enrollment_date', 'idx_enrollments_enrollment_date');
            $table->index('certificate_issued', 'idx_enrollments_certificate_issued');
            $table->index(['status', 'progress_percentage'], 'idx_enrollments_status_progress');
        });

        // events: filtered by is_published and start_date on every widget load
        Schema::table('events', function (Blueprint $table) {
            $table->index('is_published', 'idx_events_is_published');
            $table->index('start_date', 'idx_events_start_date');
            $table->index(['is_published', 'start_date'], 'idx_events_published_date');
        });

        // volunteer_applications: filtered by status and created_at
        Schema::table('volunteer_applications', function (Blueprint $table) {
            $table->index('status', 'idx_volunteers_status');
            $table->index('created_at', 'idx_volunteers_created_at');
        });

        // givings: filtered by gift_aid_eligible and given_date
        Schema::table('givings', function (Blueprint $table) {
            $table->index('gift_aid_eligible', 'idx_givings_gift_aid_eligible');
            $table->index('given_date', 'idx_givings_given_date');
            $table->index(['gift_aid_eligible', 'given_date'], 'idx_givings_eligible_date');
        });

        // newsletter_subscribers: filtered by is_active/status and subscribed_at
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->index('subscribed_at', 'idx_newsletter_subscribed_at');
            $table->index('gdpr_consent', 'idx_newsletter_gdpr_consent');
        });

        // lesson_attendances: COUNT queries on attended column
        Schema::table('lesson_attendances', function (Blueprint $table) {
            $table->index('attended', 'idx_lesson_attendances_attended');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropIndex('idx_members_is_active');
            $table->dropIndex('idx_members_created_at');
            $table->dropIndex('idx_members_date_of_birth');
            $table->dropIndex('idx_members_newsletter_consent');
        });

        Schema::table('course_enrollments', function (Blueprint $table) {
            $table->dropIndex('idx_enrollments_status');
            $table->dropIndex('idx_enrollments_enrollment_date');
            $table->dropIndex('idx_enrollments_certificate_issued');
            $table->dropIndex('idx_enrollments_status_progress');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('idx_events_is_published');
            $table->dropIndex('idx_events_start_date');
            $table->dropIndex('idx_events_published_date');
        });

        Schema::table('volunteer_applications', function (Blueprint $table) {
            $table->dropIndex('idx_volunteers_status');
            $table->dropIndex('idx_volunteers_created_at');
        });

        Schema::table('givings', function (Blueprint $table) {
            $table->dropIndex('idx_givings_gift_aid_eligible');
            $table->dropIndex('idx_givings_given_date');
            $table->dropIndex('idx_givings_eligible_date');
        });

        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->dropIndex('idx_newsletter_subscribed_at');
            $table->dropIndex('idx_newsletter_gdpr_consent');
        });

        Schema::table('lesson_attendances', function (Blueprint $table) {
            $table->dropIndex('idx_lesson_attendances_attended');
        });
    }
};

