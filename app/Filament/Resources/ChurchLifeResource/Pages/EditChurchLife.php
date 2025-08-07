<?php

namespace App\Filament\Resources\ChurchLifeResource\Pages;

use App\Filament\Resources\ChurchLifeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChurchLife extends EditRecord
{
    protected static string $resource = ChurchLifeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
