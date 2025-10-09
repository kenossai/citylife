<?php

namespace App\Filament\Resources\CafeOrderResource\Pages;

use App\Filament\Resources\CafeOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCafeOrder extends ViewRecord
{
    protected static string $resource = CafeOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('print_receipt')
                ->label('Print Receipt')
                ->icon('heroicon-o-printer')
                ->url(fn (): string => route('cafe.receipt', $this->record))
                ->openUrlInNewTab(),
        ];
    }
}
