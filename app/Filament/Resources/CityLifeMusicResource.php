<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityLifeMusicResource\Pages;
use App\Filament\Resources\CityLifeMusicResource\RelationManagers;
use App\Models\CityLifeMusic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class CityLifeMusicResource extends Resource
{
    protected static ?string $model = CityLifeMusic::class;

    protected static ?string $navigationIcon = 'heroicon-o-musical-note';

    protected static ?string $navigationGroup = 'Media Management';

    protected static ?string $navigationLabel = 'CityLife Music';

    protected static ?string $modelLabel = 'Music';

    protected static ?string $pluralModelLabel = 'CityLife Music';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $context, $state, callable $set) {
                                        if ($context === 'create') {
                                            $set('slug', \Illuminate\Support\Str::slug($state));
                                        }
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(CityLifeMusic::class, 'slug', ignoreRecord: true)
                                    ->rules(['alpha_dash']),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('artist')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('album')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('genre')
                                    ->maxLength(100),
                            ]),

                        Forms\Components\Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('citylife-music')
                            ->visibility('public')
                            ->columnSpanFull(),
                    ]),

                Section::make('Streaming Platforms')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                Forms\Components\TextInput::make('spotify_url')
                                    ->label('Spotify URL')
                                    ->url()
                                    ->maxLength(500)
                                    ->placeholder('https://open.spotify.com/track/...'),

                                Forms\Components\TextInput::make('apple_music_url')
                                    ->label('Apple Music URL')
                                    ->url()
                                    ->maxLength(500)
                                    ->placeholder('https://music.apple.com/...'),

                                Forms\Components\TextInput::make('youtube_url')
                                    ->label('YouTube URL')
                                    ->url()
                                    ->maxLength(500)
                                    ->placeholder('https://www.youtube.com/watch?v=...'),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Settings')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('is_published')
                                    ->label('Published')
                                    ->default(true),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured')
                                    ->default(false),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular()
                    ->size(50),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('artist')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('album')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('genre')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Published Status')
                    ->placeholder('All Music')
                    ->trueLabel('Published Only')
                    ->falseLabel('Unpublished Only'),

                TernaryFilter::make('is_featured')
                    ->label('Featured Status')
                    ->placeholder('All Music')
                    ->trueLabel('Featured Only')
                    ->falseLabel('Non-Featured Only'),

                SelectFilter::make('genre')
                    ->options(function () {
                        return CityLifeMusic::distinct()
                            ->whereNotNull('genre')
                            ->pluck('genre', 'genre')
                            ->toArray();
                    })
                    ->searchable()
                    ->preload(),

                SelectFilter::make('artist')
                    ->options(function () {
                        return CityLifeMusic::distinct()
                            ->whereNotNull('artist')
                            ->pluck('artist', 'artist')
                            ->toArray();
                    })
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('togglePublished')
                        ->label('Toggle Published')
                        ->icon('heroicon-o-eye')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_published' => !$record->is_published]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('toggleFeatured')
                        ->label('Toggle Featured')
                        ->icon('heroicon-o-star')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_featured' => !$record->is_featured]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListCityLifeMusic::route('/'),
            'create' => Pages\CreateCityLifeMusic::route('/create'),
            'edit' => Pages\EditCityLifeMusic::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }
}
