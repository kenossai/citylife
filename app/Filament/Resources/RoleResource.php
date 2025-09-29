<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
use App\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 1;

    protected static string $policy = \App\Policies\RolePolicy::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Role Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('e.g., manager')
                            ->helperText('Unique identifier for the role (lowercase, underscores allowed)'),

                        Forms\Components\TextInput::make('display_name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Manager')
                            ->helperText('Human-readable name for the role'),

                        Forms\Components\TextInput::make('priority')
                            ->required()
                            ->numeric()
                            ->default(100)
                            ->helperText('Higher numbers = higher priority (super_admin = 1000)'),

                        Forms\Components\ColorPicker::make('color')
                            ->default('#6B7280')
                            ->helperText('Color for role badges and displays'),

                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->placeholder('Brief description of this role and its purpose'),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Inactive roles cannot be assigned to users'),
                    ])->columns(2),

                Forms\Components\Section::make('Permissions')
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                            ->relationship(
                                'permissions',
                                'display_name'
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->display_name)
                            ->columns(2)
                            ->gridDirection('row')
                            ->bulkToggleable()
                            ->searchable()
                            ->helperText('Select the permissions this role should have'),
                    ]),

                Forms\Components\Section::make('Additional Settings')
                    ->schema([
                        Forms\Components\Textarea::make('settings')
                            ->helperText('Additional JSON settings (optional)')
                            ->placeholder('{"setting": "value"}')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ColorColumn::make('color')
                    ->label('')
                    ->width(10),

                Tables\Columns\TextColumn::make('display_name')
                    ->label('Role')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Code')
                    ->searchable()
                    ->fontFamily('mono')
                    ->color('gray')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Priority')
                    ->badge()
                    ->color(fn ($state) =>
                        $state >= 800 ? 'danger' :
                        ($state >= 600 ? 'warning' :
                        ($state >= 400 ? 'success' : 'gray'))
                    ),

                Tables\Columns\TextColumn::make('users_count')
                    ->label('Users')
                    ->counts('users')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('permissions_count')
                    ->label('Permissions')
                    ->counts('permissions')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\IconColumn::make('is_system_role')
                    ->label('System')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('')
                    ->trueColor('warning'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->disabled(fn ($record) => $record->is_system_role),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All roles')
                    ->trueLabel('Active roles')
                    ->falseLabel('Inactive roles'),

                Tables\Filters\TernaryFilter::make('is_system_role')
                    ->label('Type')
                    ->placeholder('All types')
                    ->trueLabel('System roles')
                    ->falseLabel('Custom roles'),

                Tables\Filters\Filter::make('high_priority')
                    ->label('High Priority')
                    ->query(fn (Builder $query) => $query->where('priority', '>=', 700)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Tables\Actions\DeleteAction $action, $record) {
                        if ($record->is_system_role) {
                            $action->cancel();
                            $action->sendFailureNotification('System roles cannot be deleted.');
                        }
                        if ($record->users()->count() > 0) {
                            $action->cancel();
                            $action->sendFailureNotification('Cannot delete role that is assigned to users.');
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Tables\Actions\DeleteBulkAction $action, $records) {
                            $systemRoles = $records->where('is_system_role', true);
                            if ($systemRoles->count() > 0) {
                                $action->cancel();
                                $action->sendFailureNotification('Cannot delete system roles.');
                            }

                            $rolesWithUsers = $records->filter(fn ($record) => $record->users()->count() > 0);
                            if ($rolesWithUsers->count() > 0) {
                                $action->cancel();
                                $action->sendFailureNotification('Cannot delete roles that are assigned to users.');
                            }
                        }),
                ]),
            ])
            ->defaultSort('priority', 'desc');
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'view' => Pages\ViewRole::route('/{record}'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount(['users', 'permissions']);
    }
}
