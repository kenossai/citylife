<?php

namespace App\Filament\Resources\YouthCampingRegistrationResource\Pages;

use App\Filament\Resources\YouthCampingRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListYouthCampingRegistrations extends ListRecords
{
    protected static string $resource = YouthCampingRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
