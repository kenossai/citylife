<?php

namespace App\Filament\Resources\TechnicalDepartmentMemberResource\Pages;

use App\Filament\Resources\TechnicalDepartmentMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTechnicalDepartmentMember extends EditRecord
{
    protected static string $resource = TechnicalDepartmentMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
