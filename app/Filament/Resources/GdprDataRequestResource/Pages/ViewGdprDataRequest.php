<?php

namespace App\Filament\Resources\GdprDataRequestResource\Pages;

use App\Filament\Resources\GdprDataRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGdprDataRequest extends ViewRecord
{
    protected static string $resource = GdprDataRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
