<?php

namespace App\Filament\Resources\TechnicalDepartmentResource\Pages;

use App\Filament\Resources\TechnicalDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTechnicalDepartment extends EditRecord
{
    protected static string $resource = TechnicalDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
