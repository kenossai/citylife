<?php

namespace App\Filament\Resources\GiftAidDeclarationResource\Pages;

use App\Filament\Resources\GiftAidDeclarationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGiftAidDeclaration extends EditRecord
{
    protected static string $resource = GiftAidDeclarationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
