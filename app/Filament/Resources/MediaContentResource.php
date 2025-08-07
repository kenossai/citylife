<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaContentResource\Pages;
use App\Filament\Resources\MediaContentResource\RelationManagers;
use App\Models\MediaContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MediaContentResource extends Resource
{
    protected static ?string $model = MediaContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('media_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('series_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('speaker_artist')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('release_date'),
                Forms\Components\TextInput::make('video_url')
                    ->maxLength(255),
                Forms\Components\TextInput::make('audio_url')
                    ->maxLength(255),
                Forms\Components\TextInput::make('download_url')
                    ->maxLength(255),
                Forms\Components\TextInput::make('thumbnail')
                    ->maxLength(255),
                Forms\Components\TextInput::make('duration')
                    ->numeric(),
                Forms\Components\Textarea::make('scripture_reference')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('tags'),
                Forms\Components\TextInput::make('views_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('downloads_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_featured')
                    ->required(),
                Forms\Components\Toggle::make('is_published')
                    ->required(),
                Forms\Components\TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('media_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('series_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('speaker_artist')
                    ->searchable(),
                Tables\Columns\TextColumn::make('release_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('video_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('audio_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('download_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('thumbnail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('views_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('downloads_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListMediaContents::route('/'),
            'create' => Pages\CreateMediaContent::route('/create'),
            'edit' => Pages\EditMediaContent::route('/{record}/edit'),
        ];
    }
}
