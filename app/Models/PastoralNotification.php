<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PastoralNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'pastoral_reminder_id',
        'member_id',
        'notification_type',
        'recipient_email',
        'recipient_name',
        'subject',
        'message',
        'status',
        'scheduled_for',
        'sent_at',
        'error_message',
        'metadata',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'sent_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Relationships
    public function pastoralReminder(): BelongsTo
    {
        return $this->belongsTo(PastoralReminder::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeDueNow($query)
    {
        return $query->where('scheduled_for', '<=', now())
                    ->where('status', 'pending');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('notification_type', $type);
    }

    // Accessors
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'sent' => 'success',
            'failed' => 'danger',
            'cancelled' => 'secondary',
            default => 'primary',
        };
    }

    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'pending' => 'heroicon-o-clock',
            'sent' => 'heroicon-o-check-circle',
            'failed' => 'heroicon-o-x-circle',
            'cancelled' => 'heroicon-o-minus-circle',
            default => 'heroicon-o-bell',
        };
    }

    // Methods
    public function markAsSent()
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function markAsFailed($errorMessage = null)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }

    public function markAsCancelled()
    {
        $this->update([
            'status' => 'cancelled',
        ]);
    }

    // Static Methods
    public static function getNotificationTypes()
    {
        return [
            'email' => 'Email',
            'dashboard' => 'Dashboard Notification',
            'sms' => 'SMS',
            'slack' => 'Slack Message',
        ];
    }

    public static function createForReminder(PastoralReminder $reminder, $notificationType = 'dashboard')
    {
        $member = $reminder->member;
        $scheduledFor = $reminder->notification_date;

        // Create pastoral staff notifications
        $recipients = $reminder->notification_recipients ?? [
            'admin@citylifecc.com',
            'pastor@citylifecc.com',
        ];

        foreach ($recipients as $recipient) {
            static::create([
                'pastoral_reminder_id' => $reminder->id,
                'member_id' => $member->id,
                'notification_type' => $notificationType,
                'recipient_email' => is_array($recipient) ? $recipient['email'] : $recipient,
                'recipient_name' => is_array($recipient) ? $recipient['name'] : null,
                'subject' => $reminder->reminder_type_label . ' Reminder',
                'message' => $reminder->formatted_message,
                'scheduled_for' => $scheduledFor,
                'metadata' => [
                    'reminder_type' => $reminder->reminder_type,
                    'member_name' => $member->first_name . ' ' . $member->last_name,
                    'years_count' => $reminder->years_count,
                    'reminder_date' => $reminder->reminder_date->format('Y-m-d'),
                    'notification_target' => 'staff',
                ],
            ]);
        }

        // Create member notification if enabled
        if ($reminder->send_to_member) {
            static::createForMember($reminder);
        }
    }

    public static function createForMember(PastoralReminder $reminder)
    {
        $member = $reminder->member;
        $memberScheduledFor = $reminder->member_notification_date;

        // Determine notification types for member
        $notificationTypes = [];
        switch ($reminder->member_notification_type) {
            case 'email':
                if ($member->email) $notificationTypes[] = 'email';
                break;
            case 'sms':
                if ($member->phone) $notificationTypes[] = 'sms';
                break;
            case 'both':
                if ($member->email) $notificationTypes[] = 'email';
                if ($member->phone) $notificationTypes[] = 'sms';
                break;
        }

        foreach ($notificationTypes as $type) {
            static::create([
                'pastoral_reminder_id' => $reminder->id,
                'member_id' => $member->id,
                'notification_type' => $type,
                'recipient_email' => $type === 'email' ? $member->email : null,
                'recipient_name' => $member->first_name . ' ' . $member->last_name,
                'subject' => match($reminder->reminder_type) {
                    'birthday' => '🎉 Happy Birthday from City Life!',
                    'wedding_anniversary' => '💕 Happy Anniversary!',
                    'baptism_anniversary' => '💧 Celebrating Your Baptism Anniversary',
                    'membership_anniversary' => '🏠 Happy Church Anniversary!',
                    'salvation_anniversary' => '✝️ Celebrating Your Salvation Anniversary',
                    default => 'Special Day Greetings from City Life',
                },
                'message' => $reminder->formatted_member_message,
                'scheduled_for' => $memberScheduledFor,
                'metadata' => [
                    'reminder_type' => $reminder->reminder_type,
                    'member_name' => $member->first_name . ' ' . $member->last_name,
                    'years_count' => $reminder->years_count,
                    'reminder_date' => $reminder->reminder_date->format('Y-m-d'),
                    'notification_target' => 'member',
                    'phone' => $type === 'sms' ? $member->phone : null,
                ],
            ]);
        }
    }
}
