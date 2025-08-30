<?php

namespace App\Filament\Resources\CityLifeMusicResource\Pages;

use App\Filament\Resources\CityLifeMusicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditCityLifeMusic extends EditRecord
{
    protected static string $resource = CityLifeMusicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->url(fn ($record) => route('citylife-music.show', $record->slug))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Music Updated')
            ->body('The music has been updated successfully.');
    }
}
