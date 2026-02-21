<?php

namespace App\Filament\Resources\BibleSchoolOtpTokenResource\Pages;

use App\Filament\Resources\BibleSchoolOtpTokenResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBibleSchoolOtpToken extends ViewRecord
{
    protected static string $resource = BibleSchoolOtpTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
