<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BibleSchoolVideoResource\Pages;
use App\Filament\Resources\BibleSchoolVideoResource\RelationManagers;
use App\Models\BibleSchoolVideo;
use App\Models\BibleSchoolEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BibleSchoolVideoResource extends Resource
{
    protected static ?string $model = BibleSchoolVideo::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationGroup = 'Bible School';

    protected static ?string $navigationLabel = 'Videos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Video Information')
                    ->schema([
                        Forms\Components\Select::make('bible_school_event_id')
                            ->label('Event')
                            ->relationship('event', 'title')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->helperText('Select the event/session this video belongs to'),
                        Forms\Components\Select::make('bible_school_speaker_id')
                            ->label('Speaker')
                            ->relationship('speaker', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Select the speaker for this video'),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->maxLength(65535),
                        Forms\Components\TextInput::make('video_url')
                            ->label('Video URL')
                            ->required()
                            ->url()
                            ->maxLength(255)
                            ->helperText('Enter the full URL to the video file or embed URL'),
                        Forms\Components\TextInput::make('duration')
                            ->numeric()
                            ->suffix('seconds')
                            ->helperText('Duration in seconds'),
                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail')
                            ->image()
                            ->directory('bible-school-videos')
                            ->maxSize(2048),
                    ]),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('speaker.name')
                    ->label('Speaker')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->formatStateUsing(fn ($state) => $state ? gmdate('H:i:s', $state) : '-')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('order')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('bible_school_event_id')
                    ->label('Event')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('bible_school_speaker_id')
                    ->label('Speaker')
                    ->relationship('speaker', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean(),
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
            ->defaultSort('order', 'asc');
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
            'index' => Pages\ListBibleSchoolVideos::route('/'),
            'create' => Pages\CreateBibleSchoolVideo::route('/create'),
            'edit' => Pages\EditBibleSchoolVideo::route('/{record}/edit'),
        ];
    }
}
