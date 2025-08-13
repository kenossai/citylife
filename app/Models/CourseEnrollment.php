<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'enrollment_date',
        'status',
        'progress_percentage',
        'completed_lessons',
        'completion_date',
        'certificate_issued',
        'certificate_number',
        'certificate_file_path',
        'certificate_issued_at',
        'issued_by',
        'overall_grade',
        'attendance_record',
        'payment_info',
        'notes',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'completion_date' => 'date',
        'certificate_issued_at' => 'datetime',
        'progress_percentage' => 'decimal:2',
        'overall_grade' => 'decimal:2',
        'certificate_issued' => 'boolean',
        'attendance_record' => 'array',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(Member::class, 'user_id'); // Link to Member instead of User
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    // Alias for lessonProgress for backward compatibility
    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function attendance()
    {
        return $this->hasMany(LessonAttendance::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Methods
    public function updateProgress()
    {
        // Get actual lessons count from the course
        $totalLessons = $this->course->lessons()->count();
        $completedLessons = $this->lessonProgress()->where('status', 'completed')->count();

        $progressPercentage = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

        $this->update([
            'completed_lessons' => $completedLessons,
            'progress_percentage' => $progressPercentage,
        ]);

        // Auto-complete course if all lessons are completed
        if ($completedLessons >= $totalLessons && $this->status !== 'completed') {
            $this->markAsCompleted();
        }

        // Auto-issue certificate if student attended minimum required classes
        if ($this->course->has_certificate &&
            !$this->certificate_issued &&
            $completedLessons >= $this->course->min_attendance_for_certificate) {
            $this->issueCertificate();
        }
    }

    public function updateProgressFromAttendance()
    {
        // Get total lessons count from the course
        $totalLessons = $this->course->lessons()->count();

        // Get attended lessons count
        $attendedLessons = $this->attendance()->where('attended', true)->count();

        // Calculate progress percentage based on attendance
        $progressPercentage = $totalLessons > 0 ? round(($attendedLessons / $totalLessons) * 100, 2) : 0;

        $this->update([
            'progress_percentage' => $progressPercentage,
            'completed_lessons' => $attendedLessons,
        ]);

        // Update overall grade based on quiz scores
        $this->updateOverallGrade();

        // Auto-complete course if student attended all or most lessons
        if ($attendedLessons >= $totalLessons && $this->status !== 'completed') {
            $this->markAsCompleted();
        }

        // Auto-issue certificate if student attended minimum required classes
        $minimumAttendance = $this->course->min_attendance_for_certificate ?? ceil($totalLessons * 0.8); // Default 80%
        if (!$this->certificate_issued && $attendedLessons >= $minimumAttendance) {
            $this->issueCertificate();
        }
    }

    public function updateOverallGrade()
    {
        // Get all quiz scores from lesson progress
        $quizScores = $this->lessonProgress()
            ->whereNotNull('quiz_score')
            ->pluck('quiz_score');

        if ($quizScores->count() > 0) {
            // Calculate average quiz score
            $averageGrade = round($quizScores->avg(), 2);

            $this->update([
                'overall_grade' => $averageGrade
            ]);
        }
    }    public function markAsCompleted()
    {
        $completedLessons = $this->lessonProgress()->where('status', 'completed')->count();

        $this->update([
            'status' => 'completed',
            'completion_date' => now(),
            'completed_lessons' => $completedLessons,
        ]);

        // Issue certificate if student attended minimum required classes
        if ($this->course->has_certificate && $completedLessons >= $this->course->min_attendance_for_certificate) {
            $this->issueCertificate();
        }
    }

    public function issueCertificate()
    {
        if (!$this->certificate_issued) {
            $this->update([
                'certificate_issued' => true,
                'certificate_number' => 'CERT-' . $this->course->id . '-' . $this->user->id . '-' . now()->format('Ymd'),
            ]);
        }
    }

    public function isEligibleForCertificate()
    {
        return $this->course->has_certificate &&
               $this->completed_lessons >= $this->course->min_attendance_for_certificate &&
               !$this->certificate_issued;
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'active' => '<span class="badge badge-success">Active</span>',
            'completed' => '<span class="badge badge-primary">Completed</span>',
            'dropped' => '<span class="badge badge-warning">Dropped</span>',
            'suspended' => '<span class="badge badge-danger">Suspended</span>',
            default => '<span class="badge badge-secondary">Unknown</span>',
        };
    }
}
