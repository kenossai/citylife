<?php

namespace App\Filament\Resources\CafeProductResource\Pages;

use App\Filament\Resources\CafeProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCafeProducts extends ListRecords
{
    protected static string $resource = CafeProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
