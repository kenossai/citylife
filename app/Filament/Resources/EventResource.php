<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Event Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Brief description that will appear in the events list'),
                        
                        Forms\Components\RichEditor::make('content')
                            ->label('Full Description')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'link',
                                'bulletList',
                                'orderedList',
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Date & Time')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_date')
                            ->required()
                            ->native(false),
                        
                        Forms\Components\DateTimePicker::make('end_date')
                            ->required()
                            ->native(false)
                            ->after('start_date'),
                    ])->columns(2),

                Forms\Components\Section::make('Location & Media')
                    ->schema([
                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Main Sanctuary, 123 Church St, City, State'),
                        
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Event Image')
                            ->image()
                            ->directory('events')
                            ->imageEditor()
                            ->required(),
                    ])->columns(1),

                Forms\Components\Section::make('Event Staff')
                    ->schema([
                        Forms\Components\TextInput::make('event_anchor')
                            ->label('Event Anchor/Host')
                            ->maxLength(255)
                            ->placeholder('Person anchoring/hosting the event'),
                        
                        Forms\Components\TextInput::make('guest_speaker')
                            ->label('Guest Speaker')
                            ->maxLength(255)
                            ->placeholder('Special guest or speaker for this event'),
                    ])->columns(2),

                Forms\Components\Section::make('Registration')
                    ->schema([
                        Forms\Components\Toggle::make('requires_registration')
                            ->label('Requires Registration')
                            ->helperText('Enable if attendees need to register for this event')
                            ->live(),
                        
                        Forms\Components\Textarea::make('registration_details')
                            ->label('Registration Instructions')
                            ->rows(3)
                            ->visible(fn (Forms\Get $get): bool => $get('requires_registration'))
                            ->helperText('Instructions for how to register'),
                        
                        Forms\Components\TextInput::make('max_attendees')
                            ->label('Maximum Attendees')
                            ->numeric()
                            ->visible(fn (Forms\Get $get): bool => $get('requires_registration'))
                            ->helperText('Leave empty for unlimited capacity'),
                    ])->columns(1),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->default(true)
                            ->helperText('Only published events will be visible on the website'),
                        
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Event')
                            ->helperText('Featured events will be highlighted'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Image')
                    ->circular()
                    ->size(60),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->weight('bold')
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->limit(40)
                    ->color('gray'),
                
                Tables\Columns\TextColumn::make('event_anchor')
                    ->label('Anchor')
                    ->searchable()
                    ->limit(20)
                    ->placeholder('—'),
                
                Tables\Columns\TextColumn::make('guest_speaker')
                    ->label('Speaker')
                    ->searchable()
                    ->limit(20)
                    ->placeholder('—'),
                
                Tables\Columns\IconColumn::make('requires_registration')
                    ->label('Registration')
                    ->boolean()
                    ->tooltip(fn ($record) => $record->requires_registration ? 'Registration Required' : 'No Registration'),
                
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published Status'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Status'),
                Tables\Filters\Filter::make('upcoming')
                    ->label('Upcoming Events')
                    ->query(fn (Builder $query): Builder => $query->where('start_date', '>=', now())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('start_date', 'asc');
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'view' => Pages\ViewEvent::route('/{record}'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
