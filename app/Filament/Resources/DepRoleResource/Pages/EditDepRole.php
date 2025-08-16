<?php

namespace App\Filament\Resources\DepRoleResource\Pages;

use App\Filament\Resources\DepRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDepRole extends EditRecord
{
    protected static string $resource = DepRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
