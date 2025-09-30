<?php

namespace App\Filament\Resources\SEOSettingsResource\Pages;

use App\Filament\Resources\SEOSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSEOSettings extends EditRecord
{
    protected static string $resource = SEOSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
