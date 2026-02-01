<?php

namespace App\Filament\Resources\BibleSchoolSpeakerResource\Pages;

use App\Filament\Resources\BibleSchoolSpeakerResource;
use Filament\Resources\Pages\ListRecords;

class ListBibleSchoolSpeakers extends ListRecords
{
    protected static string $resource = BibleSchoolSpeakerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
