<?php

namespace App\Filament\Resources\BibleSchoolSpeakerResource\Pages;

use App\Filament\Resources\BibleSchoolSpeakerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBibleSchoolSpeaker extends CreateRecord
{
    protected static string $resource = BibleSchoolSpeakerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
