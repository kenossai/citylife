<?php

namespace App\Filament\Resources\ChurchLifeResource\Pages;

use App\Filament\Resources\ChurchLifeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChurchLives extends ListRecords
{
    protected static string $resource = ChurchLifeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
