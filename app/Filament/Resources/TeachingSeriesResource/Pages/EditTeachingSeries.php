<?php

namespace App\Filament\Resources\TeachingSeriesResource\Pages;

use App\Filament\Resources\TeachingSeriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeachingSeries extends EditRecord
{
    protected static string $resource = TeachingSeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
