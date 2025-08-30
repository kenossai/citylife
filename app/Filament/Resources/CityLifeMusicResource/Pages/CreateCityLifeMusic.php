<?php

namespace App\Filament\Resources\CityLifeMusicResource\Pages;

use App\Filament\Resources\CityLifeMusicResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateCityLifeMusic extends CreateRecord
{
    protected static string $resource = CityLifeMusicResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Music Added')
            ->body('The music has been added successfully.');
    }
}
