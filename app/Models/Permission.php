<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'category',
        'description',
        'is_system_permission',
        'metadata',
    ];

    protected $casts = [
        'is_system_permission' => 'boolean',
        'metadata' => 'array',
    ];

    // Relationships
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permission')
            ->withPivot('conditions')
            ->withTimestamps();
    }

    // Scopes
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeSystem($query)
    {
        return $query->where('is_system_permission', true);
    }

    public function scopeCustom($query)
    {
        return $query->where('is_system_permission', false);
    }

    // Static methods for permission categories
    public static function getCategories(): array
    {
        return [
            'system' => 'System Administration',
            'members' => 'Member Management',
            'courses' => 'Course Management',
            'events' => 'Event Management',
            'finance' => 'Finance Management',
            'communications' => 'Communications',
            'worship' => 'Worship Management',
            'technical' => 'Technical Management',
            'pastoral' => 'Pastoral Care',
            'reports' => 'Reports & Analytics',
            'gdpr' => 'GDPR & Compliance',
        ];
    }

    // Get permissions grouped by category
    public static function getGroupedPermissions()
    {
        return static::all()->groupBy('category');
    }
}space App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //
}
