<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->before(function (Actions\DeleteAction $action, $record) {
                    if ($record->is_system_role) {
                        $action->cancel();
                        $action->sendFailureNotification('System roles cannot be deleted.');
                    }
                    if ($record->users()->count() > 0) {
                        $action->cancel();
                        $action->sendFailureNotification('Cannot delete role that is assigned to users.');
                    }
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Role Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('display_name')
                            ->label('Role Name'),

                        Infolists\Components\TextEntry::make('name')
                            ->label('Role Code')
                            ->copyable(),

                        Infolists\Components\ColorEntry::make('color')
                            ->label('Color'),

                        Infolists\Components\TextEntry::make('priority')
                            ->badge()
                            ->color(fn ($state) => $state >= 800 ? 'danger' : ($state >= 600 ? 'warning' : ($state >= 400 ? 'success' : 'gray'))),

                        Infolists\Components\TextEntry::make('description')
                            ->columnSpanFull(),

                        Infolists\Components\IconEntry::make('is_system_role')
                            ->label('System Role')
                            ->boolean(),

                        Infolists\Components\IconEntry::make('is_active')
                            ->label('Active')
                            ->boolean(),
                    ])->columns(3),

                Infolists\Components\Section::make('Statistics')
                    ->schema([
                        Infolists\Components\TextEntry::make('users_count')
                            ->label('Users Assigned')
                            ->badge()
                            ->color('info')
                            ->getStateUsing(fn ($record) => $record->users()->count()),

                        Infolists\Components\TextEntry::make('permissions_count')
                            ->label('Permissions Granted')
                            ->badge()
                            ->color('primary')
                            ->getStateUsing(fn ($record) => $record->permissions()->count()),
                    ])->columns(2),

                Infolists\Components\Section::make('Permissions')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('permissions')
                            ->schema([
                                Infolists\Components\TextEntry::make('display_name')
                                    ->label('Permission'),
                                Infolists\Components\TextEntry::make('category')
                                    ->badge(),
                                Infolists\Components\TextEntry::make('description'),
                            ])
                            ->columns(3),
                    ]),

                Infolists\Components\Section::make('Additional Settings')
                    ->schema([
                        Infolists\Components\TextEntry::make('settings')
                            ->getStateUsing(fn ($record) => $record->settings ? json_encode($record->settings, JSON_PRETTY_PRINT) : 'None')
                            ->placeholder('No additional settings')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
