<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutPageResource\Pages;
use App\Filament\Resources\AboutPageResource\RelationManagers;
use App\Models\AboutPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AboutPageResource extends Resource
{
    protected static ?string $model = AboutPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'About Page';

    protected static ?string $modelLabel = 'About Page';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('About Page Content')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Basic Information')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->default('About Us')
                                    ->helperText('The main title for the about page'),

                                Forms\Components\TextInput::make('church_name')
                                    ->required()
                                    ->maxLength(255)
                                    ->default('City Life')
                                    ->helperText('The official name of the church'),

                                Forms\Components\Textarea::make('church_description')
                                    ->maxLength(500)
                                    ->helperText('Brief description of the church (appears in the title section)'),

                                Forms\Components\RichEditor::make('introduction')
                                    ->required()
                                    ->columnSpanFull()
                                    ->helperText('Main introduction text about the church'),

                                Forms\Components\FileUpload::make('featured_image')
                                    ->image()
                                    ->directory('about-page')
                                    ->helperText('Main banner image for the about page'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Church Details')
                            ->schema([
                                Forms\Components\TextInput::make('affiliation')
                                    ->maxLength(255)
                                    ->helperText('Church affiliation or denomination'),

                                Forms\Components\TextInput::make('location_description')
                                    ->maxLength(255)
                                    ->helperText('Brief description of church location'),

                                Forms\Components\Textarea::make('address')
                                    ->rows(3)
                                    ->helperText('Full church address'),

                                Forms\Components\TextInput::make('phone_number')
                                    ->tel()
                                    ->maxLength(255)
                                    ->helperText('Main church phone number'),

                                Forms\Components\TextInput::make('email_address')
                                    ->email()
                                    ->maxLength(255)
                                    ->helperText('Main church email address'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Mission & Vision')
                            ->schema([
                                Forms\Components\TextInput::make('mission_title')
                                    ->maxLength(255)
                                    ->default('Our Mission and Vision'),

                                Forms\Components\RichEditor::make('mission_statement')
                                    ->columnSpanFull()
                                    ->helperText('Church mission statement'),

                                Forms\Components\RichEditor::make('vision_statement')
                                    ->columnSpanFull()
                                    ->helperText('Church vision statement'),

                                Forms\Components\TextInput::make('history_title')
                                    ->maxLength(255)
                                    ->default('A Journey Through Our Story'),

                                Forms\Components\TextInput::make('lead_pastor_name')
                                    ->maxLength(255)
                                    ->helperText('Name of the lead pastor'),

                                Forms\Components\TextInput::make('lead_pastor_title')
                                    ->maxLength(255)
                                    ->default('Lead Pastor')
                                    ->helperText('Title of the lead pastor'),

                                Forms\Components\FileUpload::make('lead_pastor_signature')
                                    ->image()
                                    ->directory('pastor-signatures')
                                    ->helperText('Pastor signature image'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Social Media & SEO')
                            ->schema([
                                Forms\Components\KeyValue::make('social_media_links')
                                    ->keyLabel('Platform')
                                    ->valueLabel('URL')
                                    ->helperText('Add social media links (facebook, twitter, instagram, youtube, etc.)'),

                                Forms\Components\TextInput::make('meta_title')
                                    ->maxLength(255)
                                    ->helperText('SEO meta title (if different from main title)'),

                                Forms\Components\Textarea::make('meta_description')
                                    ->maxLength(160)
                                    ->helperText('SEO meta description (max 160 characters)'),

                                Forms\Components\TagsInput::make('meta_keywords')
                                    ->helperText('SEO keywords (comma separated)'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Settings')
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->default(true)
                                    ->helperText('Whether this page is active and visible'),

                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Order for sorting (lower numbers appear first)'),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->default('about-us')
                                    ->helperText('URL slug for the page')
                                    ->unique(ignoreRecord: true),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('church_name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('featured_image')
                    ->circular()
                    ->size(50),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn (AboutPage $record): string => route('about'))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CoreValuesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAboutPages::route('/'),
            'create' => Pages\CreateAboutPage::route('/create'),
            'edit' => Pages\EditAboutPage::route('/{record}/edit'),
        ];
    }
}
