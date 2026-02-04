<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BibleSchoolSpeakerResource\Pages;
use App\Models\BibleSchoolSpeaker;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BibleSchoolSpeakerResource extends Resource
{
    protected static ?string $model = BibleSchoolSpeaker::class;

    protected static ?string $navigationIcon = 'heroicon-o-microphone';

    protected static ?string $navigationLabel = 'Speakers';

    protected static ?string $navigationGroup = 'Bible School';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Speaker Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('title')
                            ->maxLength(255)
                            ->placeholder('e.g., Pastor, Evangelist, Teacher'),

                        Forms\Components\TextInput::make('organization')
                            ->maxLength(255)
                            ->placeholder('Church or Organization'),

                        Forms\Components\Textarea::make('bio')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('photo')
                            ->image()
                            ->disk('s3')
                            ->directory('bible-school/speakers')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                            ]),
                    ]),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Only active speakers will be shown on the website'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->circular()
                    ->disk('s3')
                    ->defaultImageUrl(url('/assets/images/team/team-default.jpg')),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('organization')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueLabel('Active speakers only')
                    ->falseLabel('Inactive speakers only')
                    ->native(false),
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
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBibleSchoolSpeakers::route('/'),
            'create' => Pages\CreateBibleSchoolSpeaker::route('/create'),
            'edit' => Pages\EditBibleSchoolSpeaker::route('/{record}/edit'),
        ];
    }
}
