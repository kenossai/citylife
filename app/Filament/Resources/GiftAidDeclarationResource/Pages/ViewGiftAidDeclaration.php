<?php

namespace App\Filament\Resources\GiftAidDeclarationResource\Pages;

use App\Filament\Resources\GiftAidDeclarationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGiftAidDeclaration extends ViewRecord
{
    protected static string $resource = GiftAidDeclarationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
