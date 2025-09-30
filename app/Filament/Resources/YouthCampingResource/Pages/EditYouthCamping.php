<?php

namespace App\Filament\Resources\YouthCampingResource\Pages;

use App\Filament\Resources\YouthCampingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditYouthCamping extends EditRecord
{
    protected static string $resource = YouthCampingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
