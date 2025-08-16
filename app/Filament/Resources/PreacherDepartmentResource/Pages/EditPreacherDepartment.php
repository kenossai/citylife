<?php

namespace App\Filament\Resources\PreacherDepartmentResource\Pages;

use App\Filament\Resources\PreacherDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreacherDepartment extends EditRecord
{
    protected static string $resource = PreacherDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
