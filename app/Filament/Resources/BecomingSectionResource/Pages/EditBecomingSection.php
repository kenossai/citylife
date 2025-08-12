<?php

namespace App\Filament\Resources\BecomingSectionResource\Pages;

use App\Filament\Resources\BecomingSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBecomingSection extends EditRecord
{
    protected static string $resource = BecomingSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
