<?php

namespace App\Filament\Resources\CafeOrderResource\Pages;

use App\Filament\Resources\CafeOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCafeOrder extends EditRecord
{
    protected static string $resource = CafeOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Recalculate totals when editing
        $subtotal = 0;
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $subtotal += ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            }
        }

        $data['subtotal'] = $subtotal;
        $data['total_amount'] = $subtotal + ($data['tax_amount'] ?? 0) - ($data['discount_amount'] ?? 0);

        return $data;
    }
}
