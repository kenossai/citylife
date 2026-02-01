<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BibleSchoolEventResource\Pages;
use App\Filament\Resources\BibleSchoolEventResource\RelationManagers;
use App\Models\BibleSchoolEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BibleSchoolEventResource extends Resource
{
    protected static ?string $model = BibleSchoolEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Bible School';

    protected static ?string $navigationLabel = 'Events';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Event Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Bible School International 2026'),

                        Forms\Components\RichEditor::make('description')
                            ->label('Description')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'link',
                                'bulletList',
                                'orderedList',
                            ])
                            ->placeholder('Detailed description of the Bible School event'),

                        Forms\Components\TextInput::make('year')
                            ->required()
                            ->numeric()
                            ->default(date('Y'))
                            ->minValue(2000)
                            ->maxValue(2100)
                            ->helperText('The year this Bible School event takes place'),
                    ])->columns(2),

                Forms\Components\Section::make('Date & Location')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start Date')
                            ->native(false),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('End Date')
                            ->native(false)
                            ->after('start_date'),

                        Forms\Components\TextInput::make('location')
                            ->maxLength(255)
                            ->placeholder('e.g., Online, CityLife Church, etc.'),
                    ])->columns(3),

                Forms\Components\Section::make('Event Image')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Featured Image')
                            ->image()
                            ->directory('bible-school-events')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                            ])
                            ->maxSize(2048)
                            ->helperText('Main image for this Bible School event'),
                    ]),

                Forms\Components\Section::make('Speakers')
                    ->schema([
                        Forms\Components\Select::make('speakers')
                            ->relationship('speakers', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->maxLength(255)
                                        ->placeholder('Speaker name'),

                                    Forms\Components\TextInput::make('title')
                                        ->maxLength(255)
                                        ->placeholder('e.g., Pastor, Evangelist'),

                                    Forms\Components\TextInput::make('organization')
                                        ->maxLength(255)
                                        ->placeholder('Church or organization'),

                                    Forms\Components\TextInput::make('email')
                                        ->email()
                                        ->placeholder('Email address'),

                                    Forms\Components\TextInput::make('phone')
                                        ->tel()
                                        ->placeholder('Phone number'),
                                ]),

                                Forms\Components\Textarea::make('bio')
                                    ->label('Biography')
                                    ->rows(3)
                                    ->placeholder('Brief bio of the speaker'),

                                Forms\Components\FileUpload::make('photo')
                                    ->image()
                                    ->directory('bible-school/speakers')
                                    ->imageEditor(),
                            ])
                            ->helperText('Select speakers for this event or add new ones')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only active events will be visible on the website'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->square()
                    ->defaultImageUrl(url('/assets/images/events/event-default.jpg')),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->description(fn (BibleSchoolEvent $record): string => $record->location ?? ''),

                Tables\Columns\TextColumn::make('year')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start')
                    ->date('M d, Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('End')
                    ->date('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('speakers_count')
                    ->counts('speakers')
                    ->label('Speakers')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('videos_count')
                    ->counts('videos')
                    ->label('Videos')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('audios_count')
                    ->counts('audios')
                    ->label('Audios')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('year')
                    ->options(fn () => BibleSchoolEvent::query()
                        ->distinct()
                        ->pluck('year', 'year')
                        ->sort()
                        ->reverse())
                    ->label('Filter by Year'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->boolean()
                    ->trueLabel('Active events only')
                    ->falseLabel('Inactive events only')
                    ->native(false),

                Tables\Filters\Filter::make('has_speakers')
                    ->query(fn (Builder $query): Builder => $query->has('speakers'))
                    ->label('Has Speakers'),

                Tables\Filters\Filter::make('has_videos')
                    ->query(fn (Builder $query): Builder => $query->has('videos'))
                    ->label('Has Videos'),

                Tables\Filters\Filter::make('has_audios')
                    ->query(fn (Builder $query): Builder => $query->has('audios'))
                    ->label('Has Audios'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
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
            'index' => Pages\ListBibleSchoolEvents::route('/'),
            'create' => Pages\CreateBibleSchoolEvent::route('/create'),
            'edit' => Pages\EditBibleSchoolEvent::route('/{record}/edit'),
        ];
    }
}
