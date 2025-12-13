<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityLifeTalkTimeResource\Pages;
use App\Models\CityLifeTalkTime;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\Str;

class CityLifeTalkTimeResource extends Resource
{
    protected static ?string $model = CityLifeTalkTime::class;

    protected static ?string $navigationIcon = 'heroicon-o-microphone';

    protected static ?string $navigationLabel = 'CityLife TalkTime';

    protected static ?string $navigationGroup = 'Media Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Episode Details')
                    ->tabs([
                        Tabs\Tab::make('Basic Information')
                            ->schema([
                                Section::make('Episode Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Episode Title')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null),

                                        Forms\Components\TextInput::make('slug')
                                            ->label('URL Slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(CityLifeTalkTime::class, 'slug', ignoreRecord: true)
                                            ->rules(['alpha_dash']),

                                        Forms\Components\TextInput::make('episode_number')
                                            ->label('Episode Number (Auto-generated)')
                                            ->disabled()
                                            ->dehydrated(false)
                                            ->placeholder('Will be auto-generated')
                                            ->helperText('Episode numbers are automatically generated based on creation order'),
                                    ])
                                    ->columns(2),

                                Section::make('Participants')
                                    ->schema([
                                        Forms\Components\TextInput::make('host')
                                            ->label('Host')
                                            ->maxLength(255)
                                            ->placeholder('Main host name'),

                                        Forms\Components\TextInput::make('guest')
                                            ->label('Guest(s)')
                                            ->maxLength(255)
                                            ->placeholder('Guest speaker name(s)'),

                                        Forms\Components\DatePicker::make('episode_date')
                                            ->label('Episode Date')
                                            ->default(now()),

                                        Forms\Components\TextInput::make('duration_minutes')
                                            ->label('Duration (minutes)')
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(300)
                                            ->placeholder('45'),
                                    ])
                                    ->columns(2),

                                Section::make('Content')
                                    ->schema([
                                        Forms\Components\RichEditor::make('description')
                                            ->label('Episode Description')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tabs\Tab::make('Media & Publishing')
                            ->schema([
                                Section::make('Episode Image')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->label('Episode Thumbnail')
                                            ->image()
                                            ->directory('citylife-talktime')
                                            ->disk('s3')
                                            ->visibility('public')
                                            ->imageEditor()
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Video')
                                    ->schema([
                                        Forms\Components\TextInput::make('video_url')
                                            ->label('Video URL')
                                            ->url()
                                            ->maxLength(255)
                                            ->placeholder('https://youtube.com/watch?v=...')
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Publishing Options')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_published')
                                            ->label('Published')
                                            ->default(true)
                                            ->helperText('Episode will be visible to the public'),

                                        Forms\Components\Toggle::make('is_featured')
                                            ->label('Featured Episode')
                                            ->helperText('Featured episodes appear prominently'),

                                        Forms\Components\TextInput::make('sort_order')
                                            ->label('Sort Order')
                                            ->numeric()
                                            ->helperText('Lower numbers appear first'),
                                    ])
                                    ->columns(3),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Thumbnail')
                    ->circular()
                    ->size(50),

                Tables\Columns\TextColumn::make('title')
                    ->label('Episode Title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('episode_number')
                    ->label('Episode #')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('host')
                    ->label('Host')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('guest')
                    ->label('Guest')
                    ->searchable()
                    ->toggleable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('episode_date')
                    ->label('Episode Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' min' : '-')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-minus'),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean()
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_published')
                    ->label('Status')
                    ->options([
                        1 => 'Published',
                        0 => 'Draft',
                    ]),

                SelectFilter::make('is_featured')
                    ->label('Featured')
                    ->options([
                        1 => 'Featured',
                        0 => 'Regular',
                    ]),

                Tables\Filters\Filter::make('episode_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('From Date'),
                        Forms\Components\DatePicker::make('until')->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('episode_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('episode_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('episode_date', 'desc');
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
            'index' => Pages\ListCityLifeTalkTimes::route('/'),
            'create' => Pages\CreateCityLifeTalkTime::route('/create'),
            'edit' => Pages\EditCityLifeTalkTime::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
