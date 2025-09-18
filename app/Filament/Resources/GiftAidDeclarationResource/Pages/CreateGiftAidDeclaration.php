<?php

namespace App\Filament\Resources\GiftAidDeclarationResource\Pages;

use App\Filament\Resources\GiftAidDeclarationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGiftAidDeclaration extends CreateRecord
{
    protected static string $resource = GiftAidDeclarationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
