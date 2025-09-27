<?php

namespace App\Filament\Resources\GdprDataRequestResource\Pages;

use App\Filament\Resources\GdprDataRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGdprDataRequests extends ListRecords
{
    protected static string $resource = GdprDataRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'GDPR Data Requests';
    }
}
