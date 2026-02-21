<?php

namespace App\Filament\Resources\BibleSchoolOtpTokenResource\Pages;

use App\Filament\Resources\BibleSchoolOtpTokenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBibleSchoolOtpToken extends EditRecord
{
    protected static string $resource = BibleSchoolOtpTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
