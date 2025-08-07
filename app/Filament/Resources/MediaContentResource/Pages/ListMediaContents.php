<?php

namespace App\Filament\Resources\MediaContentResource\Pages;

use App\Filament\Resources\MediaContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMediaContents extends ListRecords
{
    protected static string $resource = MediaContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
