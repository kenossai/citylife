<?php

namespace App\Filament\Resources\PreacherDepartmentResource\Pages;

use App\Filament\Resources\PreacherDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPreacherDepartments extends ListRecords
{
    protected static string $resource = PreacherDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
