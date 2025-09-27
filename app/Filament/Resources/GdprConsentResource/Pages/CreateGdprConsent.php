<?php

namespace App\Filament\Resources\GdprConsentResource\Pages;

use App\Filament\Resources\GdprConsentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGdprConsent extends CreateRecord
{
    protected static string $resource = GdprConsentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto-populate IP address and user agent if not provided
        if (empty($data['ip_address'])) {
            $data['ip_address'] = request()->ip();
        }

        return $data;
    }
}
