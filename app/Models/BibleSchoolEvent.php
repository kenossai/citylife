<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BibleSchoolEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'year',
        'start_date',
        'end_date',
        'location',
        'image',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'year' => 'integer',
    ];

    public function videos(): HasMany
    {
        return $this->hasMany(BibleSchoolVideo::class)->orderBy('order');
    }

    public function audios(): HasMany
    {
        return $this->hasMany(BibleSchoolAudio::class)->orderBy('order');
    }

    public function accessCodes(): HasMany
    {
        return $this->hasMany(BibleSchoolAccessCode::class);
    }

    public function speakers()
    {
        return $this->belongsToMany(BibleSchoolSpeaker::class, 'bible_school_event_speaker')
            ->withPivot('order')
            ->orderBy('bible_school_event_speaker.order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }
}
