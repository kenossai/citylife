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
                        // Send birthday wish directly to member (no pastoral reminder needed)
                        $sent = [];
                        $failed = [];

                        // Send email if member has email
                        if ($record->email) {
                            try {
                                \Illuminate\Support\Facades\Mail::raw(
                                    "🎉 Happy Birthday, {$record->first_name}!\n\nWishing you a wonderful day filled with God's blessings and joy. May this new year of life bring you countless moments of happiness, love, and spiritual growth.\n\nFrom all of us at City Life Christian Centre! 🎂\n\nGod bless you on your special day!",
                                    function($message) use ($record) {
                                        $message->to($record->email)
                                                ->subject('🎉 Happy Birthday from City Life Christian Centre!')
                                                ->from('noreply@citylifecc.com', 'City Life Christian Centre');
                                    }
                                );
                                $sent[] = 'email';
                            } catch (\Exception $e) {
                                $failed[] = "email ({$e->getMessage()})";
                            }
                        }

                        // TODO: Add SMS sending if member has phone (when SMS service is configured)
                        // if ($record->phone) {
                        //     try {
                        //         // SMS sending logic here
                        //         $sent[] = 'SMS';
                        //     } catch (\Exception $e) {
                        //         $failed[] = "SMS ({$e->getMessage()})";
                        //     }
                        // }

                        // Show notification based on results
                        if (!empty($sent)) {
                            $methods = implode(' and ', $sent);
                            \Filament\Notifications\Notification::make()
                                ->title('Birthday wish sent! 🎉')
                                ->body("Birthday wishes sent to {$record->first_name} {$record->last_name} via {$methods}")
                                ->success()
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Unable to send birthday wish')
                                ->body("No valid contact method found for {$record->first_name} {$record->last_name}")
                                ->warning()
                                ->send();
                        }

                        if (!empty($failed)) {
                            $failures = implode(', ', $failed);
                            \Filament\Notifications\Notification::make()
                                ->title('Some messages failed')
                                ->body("Failed to send via: {$failures}")
                                ->warning()
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Send Birthday Wishes')
                    ->modalDescription(fn ($record) => "Send birthday wishes to {$record->first_name} {$record->last_name}?")
                    ->visible(function ($record) {
                        // Only show if birthday is today or within 1 day
                        return $this->getDaysUntilBirthday($record->date_of_birth) <= 1;
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
