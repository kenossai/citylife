<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiveStreamResource\Pages;
use App\Models\LiveStream;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class LiveStreamResource extends Resource
{
    protected static ?string $model = LiveStream::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationLabel = 'Live Streams';

    protected static ?string $modelLabel = 'Live Stream';

    protected static ?string $pluralModelLabel = 'Live Streams';

    protected static ?string $navigationGroup = 'Website';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Stream Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $context, $state, Forms\Set $set) => $context === 'edit' ? null : $set('slug', \Illuminate\Support\Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(LiveStream::class, 'slug', ignoreRecord: true),

                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('platform')
                            ->required()
                            ->options(LiveStream::getPlatforms())
                            ->default('youtube'),

                        Forms\Components\TextInput::make('stream_url')
                            ->label('Stream URL')
                            ->required()
                            ->url()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('embed_code')
                            ->label('Custom Embed Code (Optional)')
                            ->maxLength(65535)
                            ->hint('Leave empty to auto-generate based on platform and URL'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Schedule')
                    ->schema([
                        Forms\Components\DateTimePicker::make('scheduled_start')
                            ->required()
                            ->default(now()->addHour()),

                        Forms\Components\DateTimePicker::make('scheduled_end')
                            ->required()
                            ->default(now()->addHours(2)),

                        Forms\Components\Select::make('status')
                            ->required()
                            ->options([
                                'scheduled' => 'Scheduled',
                                'live' => 'Live',
                                'ended' => 'Ended',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('scheduled'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Select::make('category')
                            ->required()
                            ->options(LiveStream::getCategories())
                            ->default('service'),

                        Forms\Components\TextInput::make('estimated_viewers')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Stream'),

                        Forms\Components\Toggle::make('is_public')
                            ->label('Public Stream')
                            ->default(true),

                        Forms\Components\Toggle::make('enable_chat')
                            ->label('Enable Chat')
                            ->default(true),

                        Forms\Components\Toggle::make('auto_record')
                            ->label('Auto Record')
                            ->default(true),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\TextInput::make('thumbnail_url')
                            ->label('Thumbnail URL (Optional)')
                            ->url()
                            ->maxLength(255)
                            ->hint('Leave empty to auto-generate'),

                        Forms\Components\TextInput::make('recording_url')
                            ->label('Recording URL')
                            ->url()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('pastor_notes')
                            ->label('Pastor Notes')
                            ->maxLength(65535),

                        Forms\Components\TagsInput::make('tags')
                            ->separator(','),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_url')
                    ->label('Thumbnail')
                    ->square()
                    ->defaultImageUrl('https://via.placeholder.com/100x100?text=Stream'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\BadgeColumn::make('platform')
                    ->colors([
                        'danger' => 'youtube',
                        'info' => 'vimeo',
                        'primary' => 'facebook',
                        'secondary' => 'custom',
                    ]),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'scheduled',
                        'success' => 'live',
                        'secondary' => 'ended',
                        'danger' => 'cancelled',
                    ]),

                Tables\Columns\BadgeColumn::make('category')
                    ->colors([
                        'primary' => 'service',
                        'success' => 'prayer',
                        'warning' => 'youth',
                        'info' => 'bible_study',
                    ]),

                Tables\Columns\TextColumn::make('scheduled_start')
                    ->dateTime()
                    ->sortable()
                    ->since(),

                Tables\Columns\TextColumn::make('estimated_viewers')
                    ->label('Viewers')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured'),

                Tables\Columns\IconColumn::make('is_public')
                    ->boolean()
                    ->label('Public'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'live' => 'Live',
                        'ended' => 'Ended',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('platform')
                    ->options(LiveStream::getPlatforms()),

                Tables\Filters\SelectFilter::make('category')
                    ->options(LiveStream::getCategories()),

                Tables\Filters\Filter::make('featured')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true))
                    ->label('Featured Only'),

                Tables\Filters\Filter::make('upcoming')
                    ->query(fn (Builder $query): Builder => $query->upcoming())
                    ->label('Upcoming'),

                Tables\Filters\Filter::make('live')
                    ->query(fn (Builder $query): Builder => $query->live())
                    ->label('Currently Live'),
            ])
            ->actions([
                Tables\Actions\Action::make('start_stream')
                    ->icon('heroicon-m-play')
                    ->color('success')
                    ->action(function (LiveStream $record) {
                        $record->startStream();

                        Notification::make()
                            ->title('Stream started successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (LiveStream $record) => $record->status === 'scheduled'),

                Tables\Actions\Action::make('end_stream')
                    ->icon('heroicon-m-stop')
                    ->color('danger')
                    ->action(function (LiveStream $record) {
                        $record->endStream();

                        Notification::make()
                            ->title('Stream ended successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (LiveStream $record) => $record->status === 'live'),

                Tables\Actions\Action::make('view_stream')
                    ->icon('heroicon-m-eye')
                    ->color('info')
                    ->url(fn (LiveStream $record) => $record->stream_url)
                    ->openUrlInNewTab()
                    ->visible(fn (LiveStream $record) => in_array($record->status, ['live', 'scheduled'])),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('scheduled_start', 'desc')
            ->poll('30s'); // Auto-refresh every 30 seconds
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
            'index' => Pages\ListLiveStreams::route('/'),
            'create' => Pages\CreateLiveStream::route('/create'),
            'edit' => Pages\EditLiveStream::route('/{record}/edit'),
        ];
    }
}
