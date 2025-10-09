<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CafeSettingResource\Pages;
use App\Models\CafeSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CafeSettingResource extends Resource
{
    protected static ?string $model = CafeSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Cafe Management';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->disabled(fn ($record) => $record !== null),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->rows(2)
                            ->maxLength(500),

                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                'string' => 'Text',
                                'number' => 'Number',
                                'boolean' => 'True/False',
                                'json' => 'JSON',
                                'time' => 'Time',
                                'date' => 'Date',
                            ])
                            ->live(),
                    ])->columns(2),

                Forms\Components\Section::make('Value')
                    ->schema([
                        Forms\Components\TextInput::make('value')
                            ->label('Text Value')
                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['string', null])),

                        Forms\Components\TextInput::make('value')
                            ->label('Number Value')
                            ->numeric()
                            ->visible(fn (Forms\Get $get) => $get('type') === 'number'),

                        Forms\Components\Toggle::make('boolean_value')
                            ->label('Boolean Value')
                            ->visible(fn (Forms\Get $get) => $get('type') === 'boolean')
                            ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                                $set('value', $state ? 'true' : 'false')
                            ),

                        Forms\Components\TimePicker::make('time_value')
                            ->label('Time Value')
                            ->visible(fn (Forms\Get $get) => $get('type') === 'time')
                            ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                                $set('value', $state)
                            ),

                        Forms\Components\DatePicker::make('date_value')
                            ->label('Date Value')
                            ->visible(fn (Forms\Get $get) => $get('type') === 'date')
                            ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                                $set('value', $state)
                            ),

                        Forms\Components\Textarea::make('value')
                            ->label('JSON Value')
                            ->rows(5)
                            ->visible(fn (Forms\Get $get) => $get('type') === 'json')
                            ->helperText('Enter valid JSON format'),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_public')
                            ->label('Public Setting')
                            ->helperText('Can be accessed from frontend'),

                        Forms\Components\TextInput::make('group')
                            ->label('Group')
                            ->maxLength(255)
                            ->placeholder('e.g., general, hours, payment')
                            ->helperText('Group related settings together'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'primary' => 'string',
                        'success' => 'number',
                        'warning' => 'boolean',
                        'danger' => 'json',
                        'info' => 'time',
                        'secondary' => 'date',
                    ]),

                Tables\Columns\TextColumn::make('value')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),

                Tables\Columns\TextColumn::make('group')
                    ->badge()
                    ->placeholder('No Group'),

                Tables\Columns\IconColumn::make('is_public')
                    ->label('Public')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'string' => 'Text',
                        'number' => 'Number',
                        'boolean' => 'True/False',
                        'json' => 'JSON',
                        'time' => 'Time',
                        'date' => 'Date',
                    ]),

                Tables\Filters\TernaryFilter::make('is_public')
                    ->label('Public Setting')
                    ->placeholder('All settings')
                    ->trueLabel('Public only')
                    ->falseLabel('Private only'),

                Tables\Filters\SelectFilter::make('group')
                    ->options(fn () => CafeSetting::distinct('group')
                        ->whereNotNull('group')
                        ->pluck('group', 'group')
                        ->toArray()
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('group');
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
            'index' => Pages\ListCafeSettings::route('/'),
            'create' => Pages\CreateCafeSetting::route('/create'),
            'edit' => Pages\EditCafeSetting::route('/{record}/edit'),
        ];
    }
}
