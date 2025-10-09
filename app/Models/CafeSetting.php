<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CafeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        return static::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, $value, string $type = 'text', string $group = 'general'): void
    {
        $setting = static::firstOrNew(['key' => $key]);
        $setting->value = is_array($value) ? json_encode($value) : $value;
        $setting->type = $type;
        $setting->group = $group;
        $setting->save();
    }

    /**
     * Cast value to appropriate type.
     */
    protected static function castValue($value, string $type)
    {
        return match($type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'json' => json_decode($value, true),
            'array' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Get all settings by group.
     */
    public static function getByGroup(string $group): array
    {
        $settings = static::where('group', $group)->get();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting->key] = static::castValue($setting->value, $setting->type);
        }
        
        return $result;
    }

    /**
     * Get opening hours.
     */
    public static function getOpeningHours(): array
    {
        return static::get('opening_hours', [
            'monday' => ['open' => '08:00', 'close' => '16:00', 'closed' => false],
            'tuesday' => ['open' => '08:00', 'close' => '16:00', 'closed' => false],
            'wednesday' => ['open' => '08:00', 'close' => '16:00', 'closed' => false],
            'thursday' => ['open' => '08:00', 'close' => '16:00', 'closed' => false],
            'friday' => ['open' => '08:00', 'close' => '16:00', 'closed' => false],
            'saturday' => ['open' => '09:00', 'close' => '15:00', 'closed' => false],
            'sunday' => ['open' => '10:00', 'close' => '14:00', 'closed' => false],
        ]);
    }

    /**
     * Check if cafe is currently open.
     */
    public static function isOpen(): bool
    {
        $hours = static::getOpeningHours();
        $today = strtolower(now()->format('l'));
        
        if (!isset($hours[$today]) || $hours[$today]['closed']) {
            return false;
        }
        
        $currentTime = now()->format('H:i');
        $openTime = $hours[$today]['open'];
        $closeTime = $hours[$today]['close'];
        
        return $currentTime >= $openTime && $currentTime <= $closeTime;
    }

    /**
     * Get tax rate.
     */
    public static function getTaxRate(): float
    {
        return (float) static::get('tax_rate', 20.0); // Default 20% VAT
    }

    /**
     * Get minimum order amount.
     */
    public static function getMinimumOrderAmount(): float
    {
        return (float) static::get('minimum_order_amount', 0.0);
    }

    /**
     * Check if online ordering is enabled.
     */
    public static function isOnlineOrderingEnabled(): bool
    {
        return (bool) static::get('online_ordering_enabled', true);
    }

    /**
     * Get maximum preparation time.
     */
    public static function getMaxPreparationTime(): int
    {
        return (int) static::get('max_preparation_time', 30); // minutes
    }
}
