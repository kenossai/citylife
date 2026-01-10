<?php

namespace App\Filament\Resources\RegistrationInterestResource\Pages;

use App\Filament\Resources\RegistrationInterestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegistrationInterests extends ListRecords
{
    protected static string $resource = RegistrationInterestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
