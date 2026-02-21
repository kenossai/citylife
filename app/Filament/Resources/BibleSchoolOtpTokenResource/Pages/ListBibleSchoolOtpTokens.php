<?php

namespace App\Filament\Resources\BibleSchoolOtpTokenResource\Pages;

use App\Filament\Resources\BibleSchoolOtpTokenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBibleSchoolOtpTokens extends ListRecords
{
    protected static string $resource = BibleSchoolOtpTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
