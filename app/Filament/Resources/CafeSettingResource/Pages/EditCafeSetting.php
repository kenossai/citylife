<?php

namespace App\Filament\Resources\CafeSettingResource\Pages;

use App\Filament\Resources\CafeSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCafeSetting extends EditRecord
{
    protected static string $resource = CafeSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
