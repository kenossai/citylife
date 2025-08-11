<?php

namespace App\Filament\Resources\MailManagerResource\Pages;

use App\Filament\Resources\MailManagerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMailManager extends EditRecord
{
    protected static string $resource = MailManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
