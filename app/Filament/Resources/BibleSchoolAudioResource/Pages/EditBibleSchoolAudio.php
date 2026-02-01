<?php

namespace App\Filament\Resources\BibleSchoolAudioResource\Pages;

use App\Filament\Resources\BibleSchoolAudioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBibleSchoolAudio extends EditRecord
{
    protected static string $resource = BibleSchoolAudioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
