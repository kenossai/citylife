<?php

namespace App\Filament\Resources\VerifiedMembersResource\Pages;

use App\Filament\Resources\VerifiedMembersResource;
use Filament\Resources\Pages\ListRecords;

class ListVerifiedMembers extends ListRecords
{
    protected static string $resource = VerifiedMembersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - members are created through registration
        ];
    }
}
