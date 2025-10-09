<?php

namespace App\Filament\Resources\CafeCategoryResource\Pages;

use App\Filament\Resources\CafeCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCafeCategories extends ListRecords
{
    protected static string $resource = CafeCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
