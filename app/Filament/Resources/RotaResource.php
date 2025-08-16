<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RotaResource\Pages;
use App\Filament\Resources\RotaResource\RelationManagers;
use App\Models\Rota;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RotaResource extends Resource
{
    protected static ?string $model = Rota::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Unit Management';

    protected static ?int $navigationSort = 9;

    protected static ?string $label = 'Rotas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Rota Information')
                    ->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->placeholder('Enter rota title (e.g., "August 2025 Worship Rota")'),

            Forms\Components\Textarea::make('description')
                ->maxLength(500)
                ->columnSpanFull()
                ->placeholder('Optional description for this rota'),

            Forms\Components\Select::make('departments')
                ->label('Departments')
                ->options([
                    'worship' => 'Worship Team',
                    'technical' => 'Technical Department',
                    'preacher' => 'Preacher Department'
                ])
                ->multiple()
                ->required()
                ->helperText('Select all departments that will participate in this rota'),                        Forms\Components\DatePicker::make('start_date')
                            ->required()
                            ->native(false),

                        Forms\Components\DatePicker::make('end_date')
                            ->required()
                            ->native(false)
                            ->after('start_date'),

                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->helperText('Published rotas are visible to department members'),

                        Forms\Components\Textarea::make('notes')
                            ->placeholder('Any special notes or instructions for this rota')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Schedule')
                    ->schema([
                        Forms\Components\Placeholder::make('schedule_builder')
                            ->label('Schedule Builder')
                            ->content('Schedule assignments will be managed here. For now, create the rota and edit it to add assignments.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('departments')
                    ->badge()
                    ->separator(',')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'worship' => 'Worship',
                        'technical' => 'Technical',
                        'preacher' => 'Preacher',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'worship' => 'warning',
                        'technical' => 'danger',
                        'preacher' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published'),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Created By')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department_type')
                    ->options([
                        'worship' => 'Worship',
                        'technical' => 'Technical',
                        'preacher' => 'Preacher',
                    ]),

                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published Status')
                    ->boolean()
                    ->trueLabel('Published only')
                    ->falseLabel('Unpublished only')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\Action::make('auto_generate')
                    ->label('Auto Generate')
                    ->icon('heroicon-o-sparkles')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Auto Generate Rota')
                    ->modalDescription('This will automatically assign department members to all Sundays in the date range. Any existing assignments will be overwritten.')
                    ->modalSubmitActionLabel('Generate Rota')
                    ->action(function (Rota $record) {
                        $generator = new \App\Services\RotaGeneratorService();
                        $schedule = $generator->generateWithRandomization($record);

                        $record->update(['schedule_data' => $schedule]);

                        \Filament\Notifications\Notification::make()
                            ->title('Rota Generated Successfully!')
                            ->success()
                            ->body('The rota has been automatically generated with member assignments.')
                            ->send();
                    }),

                Tables\Actions\Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function (Rota $record) {
                        return \Maatwebsite\Excel\Facades\Excel::download(
                            new \App\Exports\RotaExport($record),
                            $record->title . '.xlsx'
                        );
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListRotas::route('/'),
            'create' => Pages\CreateRota::route('/create'),
            'edit' => Pages\EditRota::route('/{record}/edit'),
        ];
    }
}
