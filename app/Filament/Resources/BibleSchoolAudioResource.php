<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BibleSchoolAudioResource\Pages;
use App\Filament\Resources\BibleSchoolAudioResource\RelationManagers;
use App\Models\BibleSchoolAudio;
use App\Models\BibleSchoolEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BibleSchoolAudioResource extends Resource
{
    protected static ?string $model = BibleSchoolAudio::class;

    protected static ?string $navigationIcon = 'heroicon-o-musical-note';

    protected static ?string $navigationGroup = 'Bible School';

    protected static ?string $navigationLabel = 'Audios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Audio Information')
                    ->schema([
                        Forms\Components\Select::make('bible_school_event_id')
                            ->label('Event')
                            ->relationship('event', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->maxLength(65535),
                        Forms\Components\TextInput::make('audio_url')
                            ->label('Audio URL')
                            ->required()
                            ->url()
                            ->maxLength(255)
                            ->helperText('Enter the full URL to the audio file'),
                        Forms\Components\TextInput::make('duration')
                            ->numeric()
                            ->suffix('seconds')
                            ->helperText('Duration in seconds'),
                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])->columns(2),

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
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->formatStateUsing(fn ($state) => $state ? gmdate('H:i:s', $state) : '-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('order')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
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
            'index' => Pages\ListBibleSchoolAudio::route('/'),
            'create' => Pages\CreateBibleSchoolAudio::route('/create'),
            'edit' => Pages\EditBibleSchoolAudio::route('/{record}/edit'),
        ];
    }
}
