<?php

namespace App\Filament\Resources\GiftAidDeclarationResource\Pages;

use App\Filament\Resources\GiftAidDeclarationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGiftAidDeclarations extends ListRecords
{
    protected static string $resource = GiftAidDeclarationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            GiftAidDeclarationResource\Widgets\GiftAidDeclarationStatsOverview::class,
        ];
    }
}
