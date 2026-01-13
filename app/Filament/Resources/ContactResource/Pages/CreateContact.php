<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateContact extends CreateRecord
{
    protected static string $resource = ContactResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Contact Information Created')
            ->body('The contact information has been created successfully.');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // If this is being set to active, deactivate all others
        if ($data['is_active'] ?? false) {
            \App\Models\Contact::where('is_active', true)->update(['is_active' => false]);
        }

        return $data;
    }
}
