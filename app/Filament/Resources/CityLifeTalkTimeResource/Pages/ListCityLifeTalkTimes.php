<?php

namespace App\Filament\Resources\CityLifeTalkTimeResource\Pages;

use App\Filament\Resources\CityLifeTalkTimeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCityLifeTalkTimes extends ListRecords
{
    protected static string $resource = CityLifeTalkTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
