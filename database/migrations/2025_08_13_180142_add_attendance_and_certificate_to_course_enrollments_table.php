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
        Schema::table('course_enrollments', function (Blueprint $table) {
            // Check which columns don't exist before adding
            if (!Schema::hasColumn('course_enrollments', 'certificate_file_path')) {
                $table->string('certificate_file_path')->nullable()->after('attendance_record');
            }
            if (!Schema::hasColumn('course_enrollments', 'certificate_issued_at')) {
                $table->dateTime('certificate_issued_at')->nullable()->after('certificate_file_path');
            }
            if (!Schema::hasColumn('course_enrollments', 'issued_by')) {
                $table->string('issued_by')->nullable()->after('certificate_issued_at');
            }
            if (!Schema::hasColumn('course_enrollments', 'overall_grade')) {
                $table->decimal('overall_grade', 5, 2)->nullable()->after('completed_lessons');
            }
            if (!Schema::hasColumn('course_enrollments', 'certificate_number')) {
                $table->string('certificate_number')->nullable()->after('certificate_issued');
            }
            if (!Schema::hasColumn('course_enrollments', 'payment_info')) {
                $table->string('payment_info')->nullable()->after('issued_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_enrollments', function (Blueprint $table) {
            $table->dropColumn([
                'attendance_record',
                'certificate_file_path',
                'certificate_issued_at',
                'issued_by',
                'overall_grade',
                'certificate_number',
                'payment_info'
            ]);
        });
    }
};
