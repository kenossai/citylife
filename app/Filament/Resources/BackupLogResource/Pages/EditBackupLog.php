<?php

namespace App\Filament\Resources\BackupLogResource\Pages;

use App\Filament\Resources\BackupLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBackupLog extends EditRecord
{
    protected static string $resource = BackupLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
