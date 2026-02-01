<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BibleSchoolSpeaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bio',
        'photo',
        'email',
        'phone',
        'title',
        'organization',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the events this speaker is associated with.
     */
    public function events()
    {
        return $this->belongsToMany(BibleSchoolEvent::class, 'bible_school_event_speaker')
            ->withPivot('order')
            ->orderBy('bible_school_event_speaker.order');
    }

    /**
     * Scope a query to only include active speakers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the speaker's full title with organization.
     */
    public function getFullTitleAttribute()
    {
        $parts = array_filter([$this->title, $this->organization]);
        return implode(', ', $parts);
    }
}
