<?php

namespace App\Filament\Widgets;

use App\Models\PastoralReminder;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Carbon\Carbon;

class UpcomingPastoralRemindersWidget extends BaseWidget
{
    protected static ?string $heading = 'Upcoming Pastoral Reminders';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PastoralReminder::query()
                    ->active()
                    ->with(['member'])
                    ->whereRaw('DATE_ADD(reminder_date, INTERVAL -days_before_reminder DAY) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 14 DAY)')
                    ->orderByRaw('DATE_ADD(reminder_date, INTERVAL -days_before_reminder DAY) ASC')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('member.first_name')
                    ->label('Member')
                    ->formatStateUsing(fn ($record) => $record->member->first_name . ' ' . $record->member->last_name)
                    ->searchable(['first_name', 'last_name']),

                Tables\Columns\BadgeColumn::make('reminder_type')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'birthday' => '🎂 Birthday',
                        'wedding_anniversary' => '💕 Wedding Anniversary',
                        'baptism_anniversary' => '💧 Baptism Anniversary',
                        'membership_anniversary' => '🏠 Membership Anniversary',
                        'salvation_anniversary' => '✝️ Salvation Anniversary',
                        default => '📅 ' . ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->colors([
                        'primary' => 'birthday',
                        'danger' => 'wedding_anniversary',
                        'info' => 'baptism_anniversary',
                        'success' => 'membership_anniversary',
                        'warning' => 'salvation_anniversary',
                    ]),

                Tables\Columns\TextColumn::make('reminder_date')
                    ->label('Date')
                    ->date('M j')
                    ->sortable(),

                Tables\Columns\TextColumn::make('notification_date')
                    ->label('Send Reminder')
                    ->formatStateUsing(function ($record) {
                        $notificationDate = $record->reminder_date->copy()->subDays($record->days_before_reminder);
                        $isToday = $notificationDate->isToday();
                        $isPast = $notificationDate->isPast();

                        if ($isToday) {
                            return '🔔 Today';
                        } elseif ($isPast) {
                            return '⚠️ Overdue';
                        } else {
                            return $notificationDate->format('M j');
                        }
                    })
                    ->color(function ($record) {
                        $notificationDate = $record->reminder_date->copy()->subDays($record->days_before_reminder);
                        if ($notificationDate->isToday()) {
                            return 'warning';
                        } elseif ($notificationDate->isPast()) {
                            return 'danger';
                        }
                        return 'primary';
                    }),

                Tables\Columns\TextColumn::make('years_count')
                    ->label('Years')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' years' : 'N/A')
                    ->placeholder('N/A'),

                Tables\Columns\TextColumn::make('formatted_message')
                    ->label('Message Preview')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->formatted_message),
            ])
            ->actions([
                Tables\Actions\Action::make('send_now')
                    ->label('Send Now')
                    ->icon('heroicon-o-paper-airplane')
                    ->size('sm')
                    ->color('success')
                    ->action(function (PastoralReminder $record) {
                        \App\Models\PastoralNotification::createForReminder($record, 'email');
                        $record->update(['last_sent_at' => now()]);

                        \Filament\Notifications\Notification::make()
                            ->title('Reminder sent successfully')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),

                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->size('sm')
                    ->url(fn (PastoralReminder $record) => route('filament.admin.resources.pastoral-reminders.edit', $record))
            ])
            ->paginated(false);
    }
}
