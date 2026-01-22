<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventSpeaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'title',
        'image',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('assets/images/events/event-speaker-1-1.png'); // default
        }

        // If it's already a full URL, return it
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        // If the path starts with 'assets/' it's a public asset
        if (str_starts_with($this->image, 'assets/')) {
            return asset($this->image);
        }

        // Otherwise it's a storage file (S3 or local)
        try {
            return \Storage::disk('s3')->url($this->image);
        } catch (\Exception $e) {
            // Fallback to local storage if S3 fails
            return asset('storage/' . $this->image);
        }
    }
}
