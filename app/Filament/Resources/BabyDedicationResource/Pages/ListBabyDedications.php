<?php

namespace App\Filament\Resources\BabyDedicationResource\Pages;

use App\Filament\Resources\BabyDedicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBabyDedications extends ListRecords
{
    protected static string $resource = BabyDedicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
