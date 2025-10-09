<?php

namespace App\Filament\Resources\CafeProductResource\Pages;

use App\Filament\Resources\CafeProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCafeProduct extends EditRecord
{
    protected static string $resource = CafeProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
