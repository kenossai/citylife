<?php

namespace App\Filament\Resources\DepRoleResource\Pages;

use App\Filament\Resources\DepRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDepRoles extends ListRecords
{
    protected static string $resource = DepRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
