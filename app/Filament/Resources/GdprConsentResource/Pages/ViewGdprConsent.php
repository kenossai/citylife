<?php

namespace App\Filament\Resources\GdprConsentResource\Pages;

use App\Filament\Resources\GdprConsentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGdprConsent extends ViewRecord
{
    protected static string $resource = GdprConsentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
