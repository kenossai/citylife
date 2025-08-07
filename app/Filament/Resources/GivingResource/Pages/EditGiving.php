<?php

namespace App\Filament\Resources\GivingResource\Pages;

use App\Filament\Resources\GivingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGiving extends EditRecord
{
    protected static string $resource = GivingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
