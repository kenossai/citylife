<?php

namespace App\Filament\Resources;

use App\Filament\Resources\YouthCampingResource\Pages;
use App\Models\YouthCamping;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class YouthCampingResource extends Resource
{
    protected static ?string $model = YouthCamping::class;

    protected static ?string $navigationIcon = 'heroicon-o-fire';

    protected static ?string $navigationGroup = 'Youth Ministry';

    protected static ?string $navigationLabel = 'Youth Camping';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state, ?YouthCamping $record) =>
                                $set('slug', \Illuminate\Support\Str::slug($state . ' ' . (date('Y'))))),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Select::make('year')
                            ->options(array_combine(
                                range(date('Y'), date('Y') + 2),
                                range(date('Y'), date('Y') + 2)
                            ))
                            ->default(date('Y'))
                            ->required(),

                        Forms\Components\RichEditor::make('description')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Featured Image')
                            ->image()
                            ->directory('youth-camping')
                            ->imageEditor(),
                    ])->columns(2),

                Forms\Components\Section::make('Dates & Location')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_date')
                            ->required()
                            ->native(false),

                        Forms\Components\DateTimePicker::make('end_date')
                            ->required()
                            ->native(false)
                            ->after('start_date'),

                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('cost_per_person')
                            ->numeric()
                            ->prefix('£')
                            ->default(0),
                    ])->columns(2),

                Forms\Components\Section::make('Registration Settings')
                    ->schema([
                        Forms\Components\TextInput::make('max_participants')
                            ->numeric()
                            ->helperText('Leave empty for unlimited participants'),

                        Forms\Components\DateTimePicker::make('registration_opens_at')
                            ->label('Registration Opens')
                            ->native(false)
                            ->helperText('When registration becomes available'),

                        Forms\Components\DateTimePicker::make('registration_closes_at')
                            ->label('Registration Closes')
                            ->native(false)
                            ->after('registration_opens_at')
                            ->helperText('Registration deadline'),

                        Forms\Components\Toggle::make('is_registration_open')
                            ->label('Registration Open')
                            ->helperText('Manually control registration availability'),
                    ])->columns(2),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('contact_person')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('contact_email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('contact_phone')
                            ->tel()
                            ->maxLength(255),
                    ])->columns(3),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Repeater::make('requirements')
                            ->label('Requirements')
                            ->simple(
                                Forms\Components\TextInput::make('requirement')
                                    ->required()
                            )
                            ->helperText('List of requirements for participation'),

                        Forms\Components\Repeater::make('what_to_bring')
                            ->label('What to Bring')
                            ->simple(
                                Forms\Components\TextInput::make('item')
                                    ->required()
                            )
                            ->helperText('Items participants should bring'),

                        Forms\Components\Repeater::make('activities')
                            ->label('Planned Activities')
                            ->simple(
                                Forms\Components\TextInput::make('activity')
                                    ->required()
                            )
                            ->helperText('Activities planned for the camping'),
                    ])->columns(1),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->default(false)
                            ->helperText('Make this camping visible to the public'),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Image')
                    ->square()
                    ->size(60),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('year')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Dates')
                    ->formatStateUsing(fn (YouthCamping $record) =>
                        $record->start_date->format('M j') . ' - ' . $record->end_date->format('M j, Y'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('location')
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 30 ? $state : null;
                    }),

                Tables\Columns\TextColumn::make('registrations_count')
                    ->label('Registrations')
                    ->counts('confirmedRegistrations')
                    ->formatStateUsing(fn (YouthCamping $record, $state) =>
                        $state . ($record->max_participants ? '/' . $record->max_participants : '')),

                Tables\Columns\IconColumn::make('is_registration_open')
                    ->label('Reg. Open')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('registration_status_message')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        str_contains($state, 'open') => 'success',
                        str_contains($state, 'closed') => 'danger',
                        str_contains($state, 'opens') => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('year')
                    ->options(array_combine(
                        range(date('Y') - 1, date('Y') + 2),
                        range(date('Y') - 1, date('Y') + 2)
                    )),

                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published'),

                Tables\Filters\TernaryFilter::make('is_registration_open')
                    ->label('Registration Open'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('toggle_registration')
                    ->label(fn (YouthCamping $record) => $record->is_registration_open ? 'Close Registration' : 'Open Registration')
                    ->icon(fn (YouthCamping $record) => $record->is_registration_open ? 'heroicon-o-lock-closed' : 'heroicon-o-lock-open')
                    ->color(fn (YouthCamping $record) => $record->is_registration_open ? 'danger' : 'success')
                    ->action(fn (YouthCamping $record) => $record->update([
                        'is_registration_open' => !$record->is_registration_open
                    ]))
                    ->requiresConfirmation(),

                Tables\Actions\Action::make('view_registrations')
                    ->label('View Registrations')
                    ->icon('heroicon-o-users')
                    ->url(fn (YouthCamping $record) => route('filament.admin.resources.youth-camping-registrations.index', [
                        'tableFilters' => ['youth_camping_id' => ['value' => $record->id]]
                    ]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('year', 'desc');
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
            'index' => Pages\ListYouthCampings::route('/'),
            'create' => Pages\CreateYouthCamping::route('/create'),
            'edit' => Pages\EditYouthCamping::route('/{record}/edit'),
        ];
    }
}
