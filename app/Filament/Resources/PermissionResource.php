<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;
use App\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Permissions';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Permission Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('e.g., members.create')
                            ->helperText('Use dot notation for hierarchical permissions'),

                        Forms\Components\TextInput::make('display_name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Create Members')
                            ->helperText('Human-readable name for this permission'),

                        Forms\Components\Select::make('category')
                            ->required()
                            ->options(Permission::getCategories())
                            ->searchable()
                            ->helperText('Category helps organize permissions'),
                    ])->columns(2),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->maxLength(500)
                            ->columnSpanFull()
                            ->placeholder('Describe what this permission allows users to do'),

                        Forms\Components\Toggle::make('is_system_permission')
                            ->label('System Permission')
                            ->helperText('System permissions cannot be deleted')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('metadata')
                            ->label('Additional Metadata (JSON)')
                            ->helperText('Optional JSON metadata for extra configuration')
                            ->columnSpanFull()
                            ->rows(3)
                            ->placeholder('{"key": "value"}'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('display_name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'system' => 'danger',
                        'members' => 'success',
                        'courses' => 'warning',
                        'pastoral' => 'info',
                        'worship' => 'purple',
                        'technical' => 'indigo',
                        'communications' => 'pink',
                        'reports' => 'orange',
                        'gdpr' => 'gray',
                        default => 'secondary',
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),

                Tables\Columns\IconColumn::make('is_system_permission')
                    ->label('System')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(Permission::getCategories())
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('is_system_permission')
                    ->label('System Permissions')
                    ->boolean()
                    ->trueLabel('System only')
                    ->falseLabel('Custom only')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Tables\Actions\DeleteAction $action, Permission $record) {
                        if ($record->is_system_permission) {
                            $action->cancel();
                            $action->sendFailureNotification('System permissions cannot be deleted.');
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Tables\Actions\DeleteBulkAction $action, $records) {
                            $systemPermissions = $records->filter(fn ($record) => $record->is_system_permission);
                            if ($systemPermissions->isNotEmpty()) {
                                $action->cancel();
                                $action->sendFailureNotification('Cannot delete system permissions.');
                            }
                        }),
                ]),
            ])
            ->defaultSort('category')
            ->groups([
                Tables\Grouping\Group::make('category')
                    ->label('Category')
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
