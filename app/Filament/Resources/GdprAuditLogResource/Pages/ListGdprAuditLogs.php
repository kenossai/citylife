<?php

namespace App\Filament\Resources\GdprAuditLogResource\Pages;

use App\Filament\Resources\GdprAuditLogResource;
use Filament\Resources\Pages\ListRecords;

class ListGdprAuditLogs extends ListRecords
{
    protected static string $resource = GdprAuditLogResource::class;

    public function getTitle(): string
    {
        return 'GDPR Audit Logs';
    }

    protected function getHeaderActions(): array
    {
        return [
            // No create action since audit logs are created programmatically
        ];
    }
}
