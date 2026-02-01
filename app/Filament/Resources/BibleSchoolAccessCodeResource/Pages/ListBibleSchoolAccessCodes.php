<?php

namespace App\Filament\Resources\BibleSchoolAccessCodeResource\Pages;

use App\Filament\Resources\BibleSchoolAccessCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBibleSchoolAccessCodes extends ListRecords
{
    protected static string $resource = BibleSchoolAccessCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
