<?php

namespace App\Filament\Resources\PreacherDepartmentMemberResource\Pages;

use App\Filament\Resources\PreacherDepartmentMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreacherDepartmentMember extends EditRecord
{
    protected static string $resource = PreacherDepartmentMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
