<?php

namespace App\Filament\Resources\CafeSettingResource\Pages;

use App\Filament\Resources\CafeSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCafeSettings extends ListRecords
{
    protected static string $resource = CafeSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
