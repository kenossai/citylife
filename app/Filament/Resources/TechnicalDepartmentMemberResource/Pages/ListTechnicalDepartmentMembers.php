<?php

namespace App\Filament\Resources\TechnicalDepartmentMemberResource\Pages;

use App\Filament\Resources\TechnicalDepartmentMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTechnicalDepartmentMembers extends ListRecords
{
    protected static string $resource = TechnicalDepartmentMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
