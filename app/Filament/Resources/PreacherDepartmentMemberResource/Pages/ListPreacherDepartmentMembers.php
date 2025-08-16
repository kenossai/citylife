<?php

namespace App\Filament\Resources\PreacherDepartmentMemberResource\Pages;

use App\Filament\Resources\PreacherDepartmentMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPreacherDepartmentMembers extends ListRecords
{
    protected static string $resource = PreacherDepartmentMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
