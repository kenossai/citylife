<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeachingSeriesResource\Pages;
use App\Filament\Resources\TeachingSeriesResource\RelationManagers;
use App\Models\TeachingSeries;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\Str;

class TeachingSeriesResource extends Resource
{
    protected static ?string $model = TeachingSeries::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationLabel = 'Teaching Series';

    protected static ?string $modelLabel = 'Teaching Series';

    protected static ?string $pluralModelLabel = 'Teaching Series';

    protected static ?string $navigationGroup = 'Media Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Teaching Series Details')
                    ->tabs([
                        Tabs\Tab::make('Basic Information')
                            ->schema([
                                Section::make('Series Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (string $context, $state, callable $set) => 
                                                $context === 'create' ? $set('slug', Str::slug($state)) : null
                                            ),
                                        
                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(TeachingSeries::class, 'slug', ignoreRecord: true)
                                            ->rules(['alpha_dash']),
                                        
                                        Forms\Components\Select::make('pastor')
                                            ->options([
                                                'Pastor John Smith' => 'Pastor John Smith',
                                                'Pastor Mary Johnson' => 'Pastor Mary Johnson',
                                                'Pastor David Wilson' => 'Pastor David Wilson',
                                                'Guest Speaker' => 'Guest Speaker',
                                            ])
                                            ->searchable()
                                            ->allowHtml(false)
                                            ->preload(),
                                        
                                        Forms\Components\Select::make('category')
                                            ->options([
                                                'Sermons' => 'Sermons',
                                                'Bible Study' => 'Bible Study',
                                                'Devotional' => 'Devotional',
                                                'Worship' => 'Worship',
                                                'Youth' => 'Youth',
                                                'Family' => 'Family',
                                                'Evangelism' => 'Evangelism',
                                                'Prayer' => 'Prayer',
                                                'Discipleship' => 'Discipleship',
                                            ])
                                            ->searchable()
                                            ->preload(),
                                    ])
                                    ->columns(2),

                                Section::make('Content')
                                    ->schema([
                                        Forms\Components\Textarea::make('summary')
                                            ->label('Short Summary')
                                            ->maxLength(500)
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\RichEditor::make('description')
                                            ->label('Full Description')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Textarea::make('scripture_references')
                                            ->label('Scripture References')
                                            ->placeholder('e.g., John 3:16, Romans 8:28, Philippians 4:13')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tabs\Tab::make('Media & Resources')
                            ->schema([
                                Section::make('Image')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->label('Series Image')
                                            ->image()
                                            ->directory('teaching-series')
                                            ->disk('public')
                                            ->imageEditor()
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Media Links')
                                    ->schema([
                                        Forms\Components\TextInput::make('video_url')
                                            ->label('Video URL')
                                            ->url()
                                            ->maxLength(255)
                                            ->placeholder('https://youtube.com/watch?v=...'),
                                        
                                        Forms\Components\TextInput::make('audio_url')
                                            ->label('Audio URL')
                                            ->url()
                                            ->maxLength(255)
                                            ->placeholder('https://soundcloud.com/...'),
                                        
                                        Forms\Components\TextInput::make('duration_minutes')
                                            ->label('Duration (minutes)')
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(300)
                                            ->placeholder('45'),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make('Organization & Publishing')
                            ->schema([
                                Section::make('Categorization')
                                    ->schema([
                                        Forms\Components\TagsInput::make('tags')
                                            ->label('Tags')
                                            ->placeholder('Add tags...')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\DatePicker::make('series_date')
                                            ->label('Series Date')
                                            ->required()
                                            ->default(now()),
                                        
                                        Forms\Components\TextInput::make('sort_order')
                                            ->label('Sort Order')
                                            ->numeric()
                                            ->default(0)
                                            ->helperText('Lower numbers appear first'),
                                    ])
                                    ->columns(2),

                                Section::make('Publishing Options')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_published')
                                            ->label('Published')
                                            ->default(true)
                                            ->helperText('Make this series visible to the public'),
                                        
                                        Forms\Components\Toggle::make('is_featured')
                                            ->label('Featured')
                                            ->default(false)
                                            ->helperText('Show this series prominently on the homepage'),
                                        
                                        Forms\Components\TextInput::make('views_count')
                                            ->label('Views Count')
                                            ->numeric()
                                            ->default(0)
                                            ->disabled()
                                            ->helperText('Auto-updated when users view the series'),
                                    ])
                                    ->columns(2),
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
                    ->label('Image')
                    ->circular()
                    ->size(60),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('pastor')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                
                Tables\Columns\TextColumn::make('series_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} min" : '-')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('views_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                
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
                SelectFilter::make('category')
                    ->options([
                        'Sermons' => 'Sermons',
                        'Bible Study' => 'Bible Study',
                        'Devotional' => 'Devotional',
                        'Worship' => 'Worship',
                        'Youth' => 'Youth',
                        'Family' => 'Family',
                        'Evangelism' => 'Evangelism',
                        'Prayer' => 'Prayer',
                        'Discipleship' => 'Discipleship',
                    ])
                    ->multiple(),
                
                SelectFilter::make('pastor')
                    ->options([
                        'Pastor John Smith' => 'Pastor John Smith',
                        'Pastor Mary Johnson' => 'Pastor Mary Johnson',
                        'Pastor David Wilson' => 'Pastor David Wilson',
                        'Guest Speaker' => 'Guest Speaker',
                    ])
                    ->multiple(),
                
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published Status'),
                
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Status'),
            ])
            ->actions([
                ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publish Selected')
                        ->icon('heroicon-o-eye')
                        ->action(fn ($records) => $records->each->update(['is_published' => true]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('unpublish')
                        ->label('Unpublish Selected')
                        ->icon('heroicon-o-eye-slash')
                        ->action(fn ($records) => $records->each->update(['is_published' => false]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('series_date', 'desc');
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
            'index' => Pages\ListTeachingSeries::route('/'),
            'create' => Pages\CreateTeachingSeries::route('/create'),
            'edit' => Pages\EditTeachingSeries::route('/{record}/edit'),
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
