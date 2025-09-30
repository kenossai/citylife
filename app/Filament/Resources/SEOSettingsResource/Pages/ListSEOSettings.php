<?php

namespace App\Filament\Resources\SEOSettingsResource\Pages;

use App\Filament\Resources\SEOSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSEOSettings extends ListRecords
{
    protected static string $resource = SEOSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
