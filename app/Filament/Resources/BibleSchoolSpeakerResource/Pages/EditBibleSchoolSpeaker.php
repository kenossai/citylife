<?php

namespace App\Filament\Resources\BibleSchoolSpeakerResource\Pages;

use App\Filament\Resources\BibleSchoolSpeakerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBibleSchoolSpeaker extends EditRecord
{
    protected static string $resource = BibleSchoolSpeakerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
