<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class PastoralReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'reminder_type',
        'title',
        'description',
        'reminder_date',
        'days_before_reminder',
        'is_annual',
        'is_active',
        'notification_recipients',
        'custom_message',
        'last_sent_at',
        'year_created',
        'send_to_member',
        'member_notification_type',
        'member_message_template',
        'days_before_member_notification',
    ];

    protected $casts = [
        'reminder_date' => 'date',
        'is_annual' => 'boolean',
        'is_active' => 'boolean',
        'notification_recipients' => 'array',
        'custom_message' => 'array',
        'last_sent_at' => 'datetime',
        'year_created' => 'integer',
        'send_to_member' => 'boolean',
        'member_message_template' => 'array',
        'days_before_member_notification' => 'integer',
    ];

    // Relationships
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(PastoralNotification::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('reminder_type', $type);
    }

    public function scopeDueToday($query)
    {
        return $query->whereRaw('DATE_ADD(reminder_date, INTERVAL -days_before_reminder DAY) = CURDATE()');
    }

    public function scopeDueThisWeek($query)
    {
        return $query->whereRaw('DATE_ADD(reminder_date, INTERVAL -days_before_reminder DAY) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)');
    }

    // Accessors
    public function getReminderTypeLabelAttribute()
    {
        return match($this->reminder_type) {
            'birthday' => 'Birthday',
            'wedding_anniversary' => 'Wedding Anniversary',
            'baptism_anniversary' => 'Baptism Anniversary',
            'membership_anniversary' => 'Membership Anniversary',
            'salvation_anniversary' => 'Salvation Anniversary',
            'custom' => $this->title ?? 'Custom Reminder',
            default => ucfirst(str_replace('_', ' ', $this->reminder_type)),
        };
    }

    public function getNotificationDateAttribute()
    {
        // For annual reminders (like birthdays), we need to calculate the current year's date
        if ($this->is_annual && in_array($this->reminder_type, ['birthday', 'wedding_anniversary', 'baptism_anniversary', 'membership_anniversary', 'salvation_anniversary'])) {
            $currentYear = now()->year;
            $reminderThisYear = $this->reminder_date->copy()->year($currentYear);
            
            // If the reminder date has already passed this year, use next year
            if ($reminderThisYear->isPast()) {
                $reminderThisYear->addYear();
            }
            
            return $reminderThisYear->subDays($this->days_before_reminder);
        }
        
        // For one-time reminders, use the original date
        return $this->reminder_date->copy()->subDays($this->days_before_reminder);
    }

    public function getYearsCountAttribute()
    {
        if (!$this->year_created) {
            return null;
        }

        return now()->year - $this->year_created;
    }

    public function getFormattedMessageAttribute()
    {
        $member = $this->member;
        $years = $this->years_count;

        $defaultMessages = [
            'birthday' => "🎉 It's {$member->first_name} {$member->last_name}'s birthday on {$this->reminder_date->format('F j')}!",
            'wedding_anniversary' => "💕 {$member->first_name} & their spouse celebrate their " . ($years ? "{$years}th " : "") . "wedding anniversary on {$this->reminder_date->format('F j')}!",
            'baptism_anniversary' => "💧 {$member->first_name} {$member->last_name} celebrates their " . ($years ? "{$years}th " : "") . "baptism anniversary on {$this->reminder_date->format('F j')}!",
            'membership_anniversary' => "🏠 {$member->first_name} {$member->last_name} celebrates their " . ($years ? "{$years}th " : "") . "membership anniversary on {$this->reminder_date->format('F j')}!",
            'salvation_anniversary' => "✝️ {$member->first_name} {$member->last_name} celebrates their " . ($years ? "{$years}th " : "") . "salvation anniversary on {$this->reminder_date->format('F j')}!",
        ];

        if ($this->custom_message && isset($this->custom_message['message'])) {
            return $this->replacePlaceholders($this->custom_message['message']);
        }

        return $defaultMessages[$this->reminder_type] ?? $this->description;
    }

    private function replacePlaceholders($message)
    {
        $member = $this->member;
        $replacements = [
            '{first_name}' => $member->first_name,
            '{last_name}' => $member->last_name,
            '{full_name}' => $member->first_name . ' ' . $member->last_name,
            '{date}' => $this->reminder_date->format('F j'),
            '{date_full}' => $this->reminder_date->format('F j, Y'),
            '{years}' => $this->years_count ?? '',
            '{years_text}' => $this->years_count ? $this->years_count . 'th ' : '',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $message);
    }

    // Member-specific message accessor
    public function getFormattedMemberMessageAttribute()
    {
        $member = $this->member;
        $years = $this->years_count;

        // Default member messages (more personal, direct to member)
        $defaultMemberMessages = [
            'birthday' => "🎉 Happy Birthday, {$member->first_name}! Wishing you a wonderful day filled with God's blessings. From all of us at City Life Christian Centre! 🎂",
            'wedding_anniversary' => "💕 Happy " . ($years ? "{$years}th " : "") . "Wedding Anniversary, {$member->first_name}! May God continue to bless your marriage. 💑",
            'baptism_anniversary' => "💧 Celebrating your " . ($years ? "{$years}th " : "") . "Baptism Anniversary today, {$member->first_name}! What a beautiful milestone in your faith journey! ✝️",
            'membership_anniversary' => "🏠 Happy " . ($years ? "{$years}th " : "") . "Membership Anniversary, {$member->first_name}! Thank you for being such a valued part of our church family! 🙏",
            'salvation_anniversary' => "✝️ Celebrating your " . ($years ? "{$years}th " : "") . "Salvation Anniversary, {$member->first_name}! Praising God for your testimony! 🙌",
        ];

        // Use custom member message template if available
        if ($this->member_message_template && isset($this->member_message_template['message'])) {
            return $this->replacePlaceholders($this->member_message_template['message']);
        }

        return $defaultMemberMessages[$this->reminder_type] ?? $this->description;
    }

    public function getMemberNotificationDateAttribute()
    {
        // For annual reminders (like birthdays), we need to calculate the current year's date
        if ($this->is_annual && in_array($this->reminder_type, ['birthday', 'wedding_anniversary', 'baptism_anniversary', 'membership_anniversary', 'salvation_anniversary'])) {
            $currentYear = now()->year;
            $reminderThisYear = $this->reminder_date->copy()->year($currentYear);
            
            // If the reminder date has already passed this year, use next year
            if ($reminderThisYear->isPast()) {
                $reminderThisYear->addYear();
            }
            
            return $reminderThisYear->subDays($this->days_before_member_notification ?? 0);
        }
        
        // For one-time reminders, use the original date
        return $this->reminder_date->copy()->subDays($this->days_before_member_notification ?? 0);
    }

    // Static Methods
    public static function getReminderTypes()
    {
        return [
            'birthday' => 'Birthday',
            'wedding_anniversary' => 'Wedding Anniversary',
            'baptism_anniversary' => 'Baptism Anniversary',
            'membership_anniversary' => 'Membership Anniversary',
            'salvation_anniversary' => 'Salvation Anniversary',
            'custom' => 'Custom Reminder',
        ];
    }

    public static function createAutomaticReminders()
    {
        // Create birthday reminders for all members
        Member::whereNotNull('date_of_birth')
            ->whereDoesntHave('pastoralReminders', function($query) {
                $query->where('reminder_type', 'birthday');
            })
            ->chunk(100, function($members) {
                foreach ($members as $member) {
                    static::create([
                        'member_id' => $member->id,
                        'reminder_type' => 'birthday',
                        'reminder_date' => $member->date_of_birth,
                        'days_before_reminder' => 7,
                        'is_annual' => true,
                        'is_active' => true,
                    ]);
                }
            });

        // Create membership anniversary reminders
        Member::whereNotNull('membership_date')
            ->whereDoesntHave('pastoralReminders', function($query) {
                $query->where('reminder_type', 'membership_anniversary');
            })
            ->chunk(100, function($members) {
                foreach ($members as $member) {
                    static::create([
                        'member_id' => $member->id,
                        'reminder_type' => 'membership_anniversary',
                        'reminder_date' => $member->membership_date,
                        'days_before_reminder' => 7,
                        'is_annual' => true,
                        'is_active' => true,
                        'year_created' => $member->membership_date->year,
                    ]);
                }
            });

        // Create baptism anniversary reminders
        Member::whereNotNull('baptism_date')
            ->whereDoesntHave('pastoralReminders', function($query) {
                $query->where('reminder_type', 'baptism_anniversary');
            })
            ->chunk(100, function($members) {
                foreach ($members as $member) {
                    static::create([
                        'member_id' => $member->id,
                        'reminder_type' => 'baptism_anniversary',
                        'reminder_date' => $member->baptism_date,
                        'days_before_reminder' => 7,
                        'is_annual' => true,
                        'is_active' => true,
                        'year_created' => $member->baptism_date->year,
                    ]);
                }
            });
    }
}
