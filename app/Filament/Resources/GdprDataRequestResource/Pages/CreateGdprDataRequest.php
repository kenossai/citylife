<?php

namespace App\Filament\Resources\GdprDataRequestResource\Pages;

use App\Filament\Resources\GdprDataRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGdprDataRequest extends CreateRecord
{
    protected static string $resource = GdprDataRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set the requested_at timestamp if not provided
        if (empty($data['requested_at'])) {
            $data['requested_at'] = now();
        }

        return $data;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'GDPR data request created successfully';
    }
}
