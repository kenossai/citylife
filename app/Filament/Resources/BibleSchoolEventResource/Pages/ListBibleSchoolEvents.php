<?php

namespace App\Filament\Resources\BibleSchoolEventResource\Pages;

use App\Filament\Resources\BibleSchoolEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBibleSchoolEvents extends ListRecords
{
    protected static string $resource = BibleSchoolEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
