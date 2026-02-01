<?php

namespace App\Filament\Resources\BibleSchoolAccessCodeResource\Pages;

use App\Filament\Resources\BibleSchoolAccessCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBibleSchoolAccessCode extends EditRecord
{
    protected static string $resource = BibleSchoolAccessCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
