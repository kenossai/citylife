<?php

namespace App\Filament\Resources\GdprAuditLogResource\Pages;

use App\Filament\Resources\GdprAuditLogResource;
use Filament\Resources\Pages\ViewRecord;

class ViewGdprAuditLog extends ViewRecord
{
    protected static string $resource = GdprAuditLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No edit or delete actions for audit logs
        ];
    }
}
