<?php

namespace App\Filament\Resources\MailManagerResource\Pages;

use App\Filament\Resources\MailManagerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMailManagers extends ListRecords
{
    protected static string $resource = MailManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action needed for mail manager
        ];
    }
}
