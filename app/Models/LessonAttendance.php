<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_enrollment_id',
        'course_lesson_id',
        'attended',
        'attendance_date',
        'notes',
        'marked_by',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'attended' => 'boolean',
    ];

    // Relationships
    public function enrollment()
    {
        return $this->belongsTo(CourseEnrollment::class, 'course_enrollment_id');
    }

    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class, 'course_lesson_id');
    }

    // Boot method to update progress when attendance changes
    protected static function booted()
    {
        static::saved(function ($attendance) {
            $attendance->updateEnrollmentProgress();
        });

        static::deleted(function ($attendance) {
            $attendance->updateEnrollmentProgress();
        });
    }

    public function updateEnrollmentProgress()
    {
        $enrollment = $this->enrollment;
        if (!$enrollment) return;

        // Use the new attendance-based progress calculation
        $enrollment->updateProgressFromAttendance();
    }
}
