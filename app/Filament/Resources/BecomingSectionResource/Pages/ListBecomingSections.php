<?php

namespace App\Filament\Resources\BecomingSectionResource\Pages;

use App\Filament\Resources\BecomingSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBecomingSections extends ListRecords
{
    protected static string $resource = BecomingSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
