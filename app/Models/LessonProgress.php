<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_enrollment_id',
        'course_lesson_id',
        'status',
        'started_at',
        'completed_at',
        'time_spent_minutes',
        'attempts',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
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

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    // Methods
    public function markAsStarted()
    {
        if ($this->status === 'not_started') {
            $this->update([
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }
    }

    public function markAsCompleted($quizScore = null)
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'quiz_score' => $quizScore,
        ]);

        // Update enrollment progress
        $this->enrollment->updateProgress();
    }

    public function addTimeSpent($minutes)
    {
        $this->increment('time_spent_minutes', $minutes);
    }

    // Accessors
    public function getFormattedTimeSpentAttribute()
    {
        if (!$this->time_spent_minutes) return '0m';

        $hours = floor($this->time_spent_minutes / 60);
        $minutes = $this->time_spent_minutes % 60;

        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }
        return $minutes . 'm';
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'not_started' => '<span class="badge badge-secondary">Not Started</span>',
            'in_progress' => '<span class="badge badge-warning">In Progress</span>',
            'completed' => '<span class="badge badge-success">Completed</span>',
            default => '<span class="badge badge-secondary">Unknown</span>',
        };
    }

    public function getPassedAttribute()
    {
        return $this->quiz_score && $this->lesson && $this->quiz_score >= $this->lesson->passing_score;
    }
}
