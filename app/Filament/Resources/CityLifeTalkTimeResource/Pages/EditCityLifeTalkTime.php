<?php

namespace App\Filament\Resources\CityLifeTalkTimeResource\Pages;

use App\Filament\Resources\CityLifeTalkTimeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCityLifeTalkTime extends EditRecord
{
    protected static string $resource = CityLifeTalkTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
