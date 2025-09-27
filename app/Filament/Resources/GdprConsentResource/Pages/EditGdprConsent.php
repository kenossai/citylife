<?php

namespace App\Filament\Resources\GdprConsentResource\Pages;

use App\Filament\Resources\GdprConsentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGdprConsent extends EditRecord
{
    protected static string $resource = GdprConsentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
