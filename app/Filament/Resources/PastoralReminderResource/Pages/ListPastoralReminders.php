<?php

namespace App\Filament\Resources\PastoralReminderResource\Pages;

use App\Filament\Resources\PastoralReminderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\PastoralReminder;
use Filament\Notifications\Notification;

class ListPastoralReminders extends ListRecords
{
    protected static string $resource = PastoralReminderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('auto_create_reminders')
                ->label('Auto-Create Reminders')
                ->icon('heroicon-o-sparkles')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('Auto-Create Pastoral Reminders')
                ->modalDescription('This will automatically create birthday, membership anniversary, and baptism anniversary reminders for all members who don\'t already have them. Are you sure you want to continue?')
                ->action(function () {
                    PastoralReminder::createAutomaticReminders();

                    Notification::make()
                        ->title('Automatic reminders created successfully')
                        ->body('Birthday, membership, and baptism anniversary reminders have been created for eligible members.')
                        ->success()
                        ->send();
                })
                ->successNotificationTitle('Automatic reminders created'),

            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PastoralReminderResource\Widgets\PastoralReminderStatsWidget::class,
        ];
    }
}
