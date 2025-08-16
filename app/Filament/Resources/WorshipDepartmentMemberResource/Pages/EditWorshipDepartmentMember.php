<?php

namespace App\Filament\Resources\WorshipDepartmentMemberResource\Pages;

use App\Filament\Resources\WorshipDepartmentMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorshipDepartmentMember extends EditRecord
{
    protected static string $resource = WorshipDepartmentMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
