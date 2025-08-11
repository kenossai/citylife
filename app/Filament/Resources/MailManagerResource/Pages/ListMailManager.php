<?php

namespace App\Filament\Resources\MailManagerResource\Pages;

use App\Filament\Resources\MailManagerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMailManager extends ListRecords
{
    protected static string $resource = MailManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refresh')
                ->label('Refresh Inbox')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(fn() => $this->render()),
        ];
    }

    public function getTitle(): string
    {
        return 'Mail Inbox';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // You can add widgets here for statistics like unread count, etc.
        ];
    }
}
