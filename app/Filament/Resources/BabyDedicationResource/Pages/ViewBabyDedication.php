<?php

namespace App\Filament\Resources\BabyDedicationResource\Pages;

use App\Filament\Resources\BabyDedicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBabyDedication extends ViewRecord
{
    protected static string $resource = BabyDedicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
