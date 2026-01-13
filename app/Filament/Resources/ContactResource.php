<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationLabel = 'Contact Page';

    protected static ?string $modelLabel = 'Contact Information';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Church Information')
                    ->schema([
                        Forms\Components\TextInput::make('church_name')
                            ->label('Church Name')
                            ->required()
                            ->maxLength(255)
                            ->default('CityLife International Church'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Only one contact information set should be active at a time')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Address Information')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->label('Street Address')
                            ->required()
                            ->rows(2)
                            ->maxLength(500)
                            ->placeholder('1 South Parade, Spaldesmoor')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('city')
                            ->label('City')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Sheffield'),

                        Forms\Components\TextInput::make('postal_code')
                            ->label('Postal Code')
                            ->required()
                            ->maxLength(20)
                            ->placeholder('S3 8ZZ'),

                        Forms\Components\TextInput::make('country')
                            ->label('Country')
                            ->required()
                            ->maxLength(255)
                            ->default('United Kingdom'),

                        Forms\Components\Textarea::make('directions')
                            ->label('Directions')
                            ->helperText('How to get to the church')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('parking_info')
                            ->label('Parking Information')
                            ->helperText('Information about parking facilities')
                            ->rows(2)
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Contact Details')
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('0114 272 8243')
                            ->prefixIcon('heroicon-o-phone'),

                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('office@citylifecc.com')
                            ->prefixIcon('heroicon-o-envelope'),

                        Forms\Components\TextInput::make('website_url')
                            ->label('Website URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://www.citylifecc.com')
                            ->prefixIcon('heroicon-o-globe-alt'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Office & Service Information')
                    ->schema([
                        Forms\Components\Textarea::make('office_hours')
                            ->label('Office Hours')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Mon - Sat: 10:00 am – 3:00 pm')
                            ->helperText('Enter office opening hours'),

                        Forms\Components\KeyValue::make('service_times')
                            ->label('Service Times')
                            ->helperText('Add service day and time (e.g., Sunday: 10:00 AM & 5:00 PM)')
                            ->keyLabel('Day')
                            ->valueLabel('Time')
                            ->addActionLabel('Add Service Time')
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Map & Location')
                    ->schema([
                        Forms\Components\TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->maxLength(20)
                            ->placeholder('53.388424')
                            ->helperText('For Google Maps integration'),

                        Forms\Components\TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->maxLength(20)
                            ->placeholder('-1.476495')
                            ->helperText('For Google Maps integration'),

                        Forms\Components\Textarea::make('map_embed_url')
                            ->label('Google Maps Embed URL')
                            ->rows(3)
                            ->maxLength(2000)
                            ->placeholder('https://www.google.com/maps/embed?pb=...')
                            ->helperText('Full embed URL from Google Maps')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Social Media Links')
                    ->schema([
                        Forms\Components\TextInput::make('facebook_url')
                            ->label('Facebook Page URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://facebook.com/citylifechurch')
                            ->prefixIcon('heroicon-o-link'),

                        Forms\Components\TextInput::make('twitter_url')
                            ->label('Twitter/X Profile URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://twitter.com/citylifechurch')
                            ->prefixIcon('heroicon-o-link'),

                        Forms\Components\TextInput::make('instagram_url')
                            ->label('Instagram Profile URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://instagram.com/citylifechurch')
                            ->prefixIcon('heroicon-o-link'),

                        Forms\Components\TextInput::make('youtube_url')
                            ->label('YouTube Channel URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://youtube.com/@citylifechurch')
                            ->prefixIcon('heroicon-o-link'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('church_name')
                    ->label('Church Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('city')
                    ->label('Location')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Contact $record): string => $record->postal_code ?? ''),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->icon('heroicon-o-phone')
                    ->copyable()
                    ->copyMessage('Phone number copied!')
                    ->copyMessageDuration(1500),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->icon('heroicon-o-envelope')
                    ->copyable()
                    ->copyMessage('Email copied!')
                    ->copyMessageDuration(1500),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('is_active', 'desc');
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
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'view' => Pages\ViewContact::route('/{record}'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count() > 0
            ? 'Active'
            : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('is_active', true)->count() > 0
            ? 'success'
            : 'warning';
    }
}
