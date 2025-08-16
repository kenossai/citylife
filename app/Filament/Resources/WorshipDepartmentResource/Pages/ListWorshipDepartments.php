<?php

namespace App\Filament\Resources\WorshipDepartmentResource\Pages;

use App\Filament\Resources\WorshipDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorshipDepartments extends ListRecords
{
    protected static string $resource = WorshipDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
