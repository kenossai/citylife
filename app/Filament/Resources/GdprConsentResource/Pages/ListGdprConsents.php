<?php

namespace App\Filament\Resources\GdprConsentResource\Pages;

use App\Filament\Resources\GdprConsentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGdprConsents extends ListRecords
{
    protected static string $resource = GdprConsentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
