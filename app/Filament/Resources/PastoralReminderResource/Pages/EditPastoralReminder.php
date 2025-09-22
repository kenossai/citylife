<?php

namespace App\Filament\Resources\PastoralReminderResource\Pages;

use App\Filament\Resources\PastoralReminderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPastoralReminder extends EditRecord
{
    protected static string $resource = PastoralReminderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
