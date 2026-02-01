<?php

namespace App\Filament\Resources\BibleSchoolVideoResource\Pages;

use App\Filament\Resources\BibleSchoolVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBibleSchoolVideos extends ListRecords
{
    protected static string $resource = BibleSchoolVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
