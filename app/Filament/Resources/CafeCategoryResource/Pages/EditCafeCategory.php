<?php

namespace App\Filament\Resources\CafeCategoryResource\Pages;

use App\Filament\Resources\CafeCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCafeCategory extends EditRecord
{
    protected static string $resource = CafeCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
