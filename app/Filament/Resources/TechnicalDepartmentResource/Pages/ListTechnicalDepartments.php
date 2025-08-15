<?php

namespace App\Filament\Resources\TechnicalDepartmentResource\Pages;

use App\Filament\Resources\TechnicalDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTechnicalDepartments extends ListRecords
{
    protected static string $resource = TechnicalDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
