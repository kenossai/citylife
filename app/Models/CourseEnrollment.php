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
        'notes',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'completion_date' => 'date',
        'progress_percentage' => 'decimal:2',
        'overall_grade' => 'decimal:2',
        'certificate_issued' => 'boolean',
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
