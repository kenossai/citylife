<?php

namespace App\Filament\Resources\CafeOrderResource\Pages;

use App\Filament\Resources\CafeOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCafeOrder extends CreateRecord
{
    protected static string $resource = CafeOrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Calculate totals before saving
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
