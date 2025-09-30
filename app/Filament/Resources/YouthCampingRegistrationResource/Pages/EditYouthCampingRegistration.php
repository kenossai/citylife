<?php

namespace App\Filament\Resources\YouthCampingRegistrationResource\Pages;

use App\Filament\Resources\YouthCampingRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditYouthCampingRegistration extends EditRecord
{
    protected static string $resource = YouthCampingRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
