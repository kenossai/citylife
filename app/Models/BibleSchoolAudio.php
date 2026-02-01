<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BibleSchoolAudio extends Model
{
    use HasFactory;

    protected $table = 'bible_school_audios';

    protected $fillable = [
        'bible_school_event_id',
        'title',
        'description',
        'audio_url',
        'duration',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration' => 'integer',
        'order' => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(BibleSchoolEvent::class, 'bible_school_event_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration) {
            return '';
        }

        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
