<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SEOSettingsResource\Pages;
use App\Models\SEOSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SEOSettingsResource extends Resource
{
    protected static ?string $model = SEOSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'SEO Settings';

    protected static ?int $navigationGroupSort = 40;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic SEO Settings')
                    ->description('Configure basic SEO meta information for your website')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->label('Site Name')
                            ->required()
                            ->maxLength(255)
                            ->default('City Life International Church'),

                        Forms\Components\Textarea::make('site_description')
                            ->label('Site Description')
                            ->helperText('This will be used as the default meta description')
                            ->rows(3)
                            ->maxLength(160),

                        Forms\Components\Textarea::make('default_keywords')
                            ->label('Default Keywords')
                            ->helperText('Comma-separated list of default keywords')
                            ->rows(2),

                        Forms\Components\FileUpload::make('default_og_image')
                            ->label('Default Social Media Image')
                            ->helperText('This image will be used when pages don\'t have a specific image')
                            ->image()
                            ->disk('s3')
                            ->visibility('public')
                            ->directory('seo/og-images')
                            ->imageEditor(),
                    ]),

                Forms\Components\Section::make('Analytics & Tracking')
                    ->description('Configure analytics and tracking codes')
                    ->schema([
                        Forms\Components\TextInput::make('google_analytics_id')
                            ->label('Google Analytics ID')
                            ->helperText('Format: G-XXXXXXXXXX or UA-XXXXXXXX-X')
                            ->placeholder('G-XXXXXXXXXX'),

                        Forms\Components\TextInput::make('google_search_console_id')
                            ->label('Google Search Console ID')
                            ->helperText('Used for site verification'),
                    ]),

                Forms\Components\Section::make('Social Media')
                    ->description('Configure social media integration')
                    ->schema([
                        Forms\Components\TextInput::make('facebook_app_id')
                            ->label('Facebook App ID')
                            ->helperText('Used for Facebook Open Graph'),

                        Forms\Components\TextInput::make('twitter_handle')
                            ->label('Twitter Handle')
                            ->helperText('Include the @ symbol')
                            ->placeholder('@CityLifeChurch'),
                    ]),

                Forms\Components\Section::make('Advanced Settings')
                    ->description('Advanced SEO configuration')
                    ->schema([
                        Forms\Components\Textarea::make('robots_txt_custom')
                            ->label('Custom Robots.txt Content')
                            ->helperText('Additional content to append to robots.txt')
                            ->rows(4),

                        Forms\Components\KeyValue::make('schema_organization')
                            ->label('Organization Schema Data')
                            ->helperText('JSON-LD structured data for your organization')
                            ->keyLabel('Property')
                            ->valueLabel('Value'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('site_name')
                    ->label('Site Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('site_description')
                    ->label('Description')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),
                Tables\Columns\IconColumn::make('google_analytics_id')
                    ->label('Analytics')
                    ->boolean()
                    ->getStateUsing(fn (SEOSettings $record): bool => !empty($record->google_analytics_id)),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
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
            'index' => Pages\ListSEOSettings::route('/'),
            'create' => Pages\CreateSEOSettings::route('/create'),
            'edit' => Pages\EditSEOSettings::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        // Only allow one SEO settings record
        return SEOSettings::count() === 0;
    }
}
