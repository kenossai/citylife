<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'instructor',
        'category',
        'duration_weeks',
        'schedule',
        'start_date',
        'end_date',
        'location',
        'featured_image',
        'requirements',
        'what_you_learn',
        'course_objectives',
        'current_enrollments',
        'has_certificate',
        'min_attendance_for_certificate',
        'is_registration_open',
        'sort_order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'has_certificate' => 'boolean',
        'is_registration_open' => 'boolean',
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        // Always auto-generate slug from title
        $this->attributes['slug'] = Str::slug($value);
    }

    // Relationships
    public function lessons()
    {
        return $this->hasMany(CourseLesson::class)->orderBy('lesson_number');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function activeEnrollments()
    {
        return $this->hasMany(CourseEnrollment::class)->where('status', 'active');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_registration_open', true);
    }

    public function scopeRegistrationOpen($query)
    {
        return $query->where('is_registration_open', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessors
    public function getTotalLessonsAttribute()
    {
        return $this->lessons()->count();
    }
}
