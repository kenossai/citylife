<?php

namespace App\Filament\Resources\WorshipDepartmentResource\Pages;

use App\Filament\Resources\WorshipDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorshipDepartment extends EditRecord
{
    protected static string $resource = WorshipDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
