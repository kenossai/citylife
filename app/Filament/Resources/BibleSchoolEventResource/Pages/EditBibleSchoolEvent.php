<?php

namespace App\Filament\Resources\BibleSchoolEventResource\Pages;

use App\Filament\Resources\BibleSchoolEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBibleSchoolEvent extends EditRecord
{
    protected static string $resource = BibleSchoolEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
