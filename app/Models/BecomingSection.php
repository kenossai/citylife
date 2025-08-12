<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BecomingSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'tagline',
        'title',
        'title_highlight',
        'description',
        'volunteer_title',
        'volunteer_icon',
        'new_member_title',
        'new_member_icon',
        'background_image',
        'left_image',
        'right_image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getActiveSection()
    {
        return self::where('is_active', true)->first() ?? self::first();
    }
}
