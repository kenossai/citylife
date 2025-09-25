<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;

class UpcomingBirthdaysWidget extends BaseWidget
{
    protected static ?string $heading = '🎂 Upcoming Birthdays';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Member::query()
                    ->active()
                    ->whereNotNull('date_of_birth')
                    ->whereRaw('
                        CASE
                            WHEN DAYOFYEAR(date_of_birth) >= DAYOFYEAR(CURDATE())
                            THEN DAYOFYEAR(date_of_birth) - DAYOFYEAR(CURDATE())
                            ELSE (365 - DAYOFYEAR(CURDATE())) + DAYOFYEAR(date_of_birth)
                        END <= 30
                    ')
                    ->orderByRaw('
                        CASE
                            WHEN DAYOFYEAR(date_of_birth) >= DAYOFYEAR(CURDATE())
                            THEN DAYOFYEAR(date_of_birth) - DAYOFYEAR(CURDATE())
                            ELSE (365 - DAYOFYEAR(CURDATE())) + DAYOFYEAR(date_of_birth)
                        END ASC
                    ')
                    ->limit(15)
            )
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Member')
                    ->formatStateUsing(fn ($record) => trim($record->title . ' ' . $record->first_name . ' ' . $record->last_name))
                    ->searchable(['first_name', 'last_name'])
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('age')
                    ->label('Turning')
                    ->formatStateUsing(function ($record) {
                        $nextBirthday = $this->getNextBirthdayDate($record->date_of_birth);
                        $age = $nextBirthday->year - $record->date_of_birth->year;
                        return $age . ' years old';
                    })
                    ->color('primary'),

                Tables\Columns\TextColumn::make('birthday_date')
                    ->label('Birthday')
                    ->formatStateUsing(function ($record) {
                        return $record->date_of_birth->format('M j');
                    })
                    ->badge()
                    ->color(function ($record) {
                        $daysUntil = $this->getDaysUntilBirthday($record->date_of_birth);
                        if ($daysUntil == 0) return 'danger';  // Today
                        if ($daysUntil <= 3) return 'warning'; // This week
                        if ($daysUntil <= 7) return 'info';    // Next week
                        return 'gray';                          // Later
                    }),

                Tables\Columns\TextColumn::make('days_until')
                    ->label('Days Until')
                    ->formatStateUsing(function ($record) {
                        $daysUntil = $this->getDaysUntilBirthday($record->date_of_birth);
                        if ($daysUntil == 0) return '🎉 Today!';
                        if ($daysUntil == 1) return '📅 Tomorrow';
                        if ($daysUntil <= 7) return "📆 {$daysUntil} days";
                        return "🗓️ {$daysUntil} days";
                    })
                    ->color(function ($record) {
                        $daysUntil = $this->getDaysUntilBirthday($record->date_of_birth);
                        if ($daysUntil == 0) return 'danger';
                        if ($daysUntil <= 3) return 'warning';
                        if ($daysUntil <= 7) return 'info';
                        return 'gray';
                    }),

                Tables\Columns\TextColumn::make('membership_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state)))
                    ->color(fn (string $state): string => match ($state) {
                        'member' => 'success',
                        'regular_attendee' => 'primary',
                        'visitor' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('contact_info')
                    ->label('Contact')
                    ->formatStateUsing(function ($record) {
                        $contact = [];
                        if ($record->phone) $contact[] = '📞';
                        if ($record->email) $contact[] = '📧';
                        return implode(' ', $contact) ?: '❌';
                    })
                    ->tooltip(function ($record) {
                        $tooltip = [];
                        if ($record->phone) $tooltip[] = 'Phone: ' . $record->phone;
                        if ($record->email) $tooltip[] = 'Email: ' . $record->email;
                        return implode(' | ', $tooltip) ?: 'No contact information';
                    }),
            ])
            ->actions([
                Action::make('send_birthday_wish')
                    ->label('Send Wish')
                    ->icon('heroicon-o-heart')
                    ->color('success')
                    ->size('sm')
                    ->action(function (Member $record) {
                        $nextBirthday = $this->getNextBirthdayDate($record->date_of_birth);

                        // Check if a birthday reminder already exists for this member and date
                        $existingReminder = \App\Models\PastoralReminder::where('member_id', $record->id)
                            ->where('reminder_type', 'birthday')
                            ->where('reminder_date', $nextBirthday->format('Y-m-d'))
                            ->where('is_active', true)
                            ->first();

                        if ($existingReminder) {
                            // If reminder exists, just update the last sent time and resend
                            $reminder = $existingReminder;
                            $reminder->update(['last_sent_at' => now()]);
                        } else {
                            // Create a new birthday pastoral reminder
                            $reminder = \App\Models\PastoralReminder::create([
                                'member_id' => $record->id,
                                'reminder_type' => 'birthday',
                                'reminder_date' => $nextBirthday,
                                'days_before_reminder' => 0, // Send today
                                'is_annual' => true,
                                'is_active' => true,
                                'send_to_member' => true,
                                'member_notification_type' => 'email',
                                'notification_recipients' => [
                                    'admin@citylifecc.com',
                                    'pastor@citylifecc.com'
                                ],
                                'member_message_template' => [
                                    'message' => '🎉 Happy Birthday, {first_name}! Wishing you a wonderful day filled with God\'s blessings. From all of us at City Life Christian Centre! 🎂'
                                ]
                            ]);
                        }

                        // Send the notification immediately
                        $notifications = \App\Models\PastoralNotification::createForReminder($reminder, 'email');
                        $reminder->update(['last_sent_at' => now()]);

                        // Send the emails immediately
                        $pendingNotifications = \App\Models\PastoralNotification::where('pastoral_reminder_id', $reminder->id)
                            ->where('status', 'pending')
                            ->get();

                        foreach ($pendingNotifications as $notification) {
                            try {
                                \Illuminate\Support\Facades\Mail::to($notification->recipient_email)
                                    ->send(new \App\Mail\PastoralReminderMail($notification));

                                $notification->update([
                                    'status' => 'sent',
                                    'sent_at' => now()
                                ]);
                            } catch (\Exception $e) {
                                $notification->update([
                                    'status' => 'failed',
                                    'error_message' => $e->getMessage()
                                ]);
                            }
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Birthday wish sent! 🎉')
                            ->body("Birthday wishes sent to {$record->first_name} {$record->last_name}")
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Send Birthday Wishes')
                    ->modalDescription(fn ($record) => "Send birthday wishes to {$record->first_name} {$record->last_name}?")
                    ->visible(function ($record) {
                        // Only show if birthday is today or within 3 days
                        return $this->getDaysUntilBirthday($record->date_of_birth) <= 3;
                    }),

                Action::make('call')
                    ->label('Call')
                    ->icon('heroicon-o-phone')
                    ->color('info')
                    ->size('sm')
                    ->url(fn (Member $record) => $record->phone ? 'tel:' . $record->phone : '#')
                    ->openUrlInNewTab(false)
                    ->visible(fn ($record) => !empty($record->phone)),

                Action::make('email')
                    ->label('Email')
                    ->icon('heroicon-o-envelope')
                    ->color('warning')
                    ->size('sm')
                    ->url(fn (Member $record) => $record->email ? 'mailto:' . $record->email : '#')
                    ->openUrlInNewTab(false)
                    ->visible(fn ($record) => !empty($record->email)),

                Action::make('view_member')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->size('sm')
                    ->url(fn (Member $record) => route('filament.admin.resources.members.edit', $record))
                    ->openUrlInNewTab(false),
            ])
            ->paginated(false)
            ->poll('30s')
            ->emptyStateHeading('No upcoming birthdays')
            ->emptyStateDescription('No member birthdays in the next 30 days.')
            ->emptyStateIcon('heroicon-o-cake');
    }

    private function getDaysUntilBirthday(Carbon $dateOfBirth): int
    {
        $today = Carbon::today();
        $thisYearBirthday = $dateOfBirth->copy()->year($today->year);

        if ($thisYearBirthday->isPast()) {
            $thisYearBirthday->addYear();
        }

        return $today->diffInDays($thisYearBirthday);
    }

    private function getNextBirthdayDate(Carbon $dateOfBirth): Carbon
    {
        $today = Carbon::today();
        $thisYearBirthday = $dateOfBirth->copy()->year($today->year);

        if ($thisYearBirthday->isPast()) {
            $thisYearBirthday->addYear();
        }

        return $thisYearBirthday;
    }

    public function getTableRecordKey($record): string
    {
        return $record->id;
    }
}
