<?php

namespace App\Filament\Resources\RegistrationInterestResource\Pages;

use App\Filament\Resources\RegistrationInterestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegistrationInterest extends EditRecord
{
    protected static string $resource = RegistrationInterestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
