<?php

namespace App\Filament\Resources\CityLifeMusicResource\Pages;

use App\Filament\Resources\CityLifeMusicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCityLifeMusic extends ListRecords
{
    protected static string $resource = CityLifeMusicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Music'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Music'),
            'published' => Tab::make('Published')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_published', true)),
            'featured' => Tab::make('Featured')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_featured', true)),
            'unpublished' => Tab::make('Unpublished')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_published', false)),
        ];
    }
}
