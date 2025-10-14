<?php

namespace App\Filament\Resources\BabyDedicationResource\Pages;

use App\Filament\Resources\BabyDedicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBabyDedication extends EditRecord
{
    protected static string $resource = BabyDedicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
