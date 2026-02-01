<?php

namespace App\Filament\Resources\BibleSchoolAudioResource\Pages;

use App\Filament\Resources\BibleSchoolAudioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBibleSchoolAudio extends ListRecords
{
    protected static string $resource = BibleSchoolAudioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
