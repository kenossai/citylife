<?php

namespace App\Filament\Resources\CafeOrderResource\Pages;

use App\Filament\Resources\CafeOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCafeOrders extends ListRecords
{
    protected static string $resource = CafeOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
